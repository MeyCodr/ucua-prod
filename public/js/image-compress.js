/**
 * Shrinks photos in the browser before they are uploaded.
 *
 * Phone cameras produce 3-12MB photos (and HEIC on iPhones / some Androids), which blow
 * past the upload limits and the accepted formats. Any <input type="file" data-compress>
 * gets its pictures resized to at most MAX_SIDE px and re-encoded as JPEG, so each one
 * ends up around 200-500KB.
 *
 * The input's own change handlers run only after the files have been swapped, so their
 * size/count checks see the compressed files. Forms can't be submitted while it's working.
 * Anything the browser can't decode (e.g. HEIC outside Safari) is left untouched for the
 * server-side validation to report.
 */
(function () {
    var MAX_SIDE = 1600;
    var QUALITY = 0.8;
    var SKIP_BELOW = 500 * 1024; // already small enough, keep the original
    var busy = 0;

    function loadImage(file) {
        return new Promise(function (resolve, reject) {
            var url = URL.createObjectURL(file);
            var img = new Image();
            img.onload = function () { URL.revokeObjectURL(url); resolve(img); };
            img.onerror = function () { URL.revokeObjectURL(url); reject(new Error('decode failed')); };
            img.src = url;
        });
    }

    function compress(file) {
        // GIFs may be animated; flattening them would lose the animation.
        if (!/^image\//.test(file.type) && !/\.(heic|heif)$/i.test(file.name)) return Promise.resolve(file);
        if (file.type === 'image/gif') return Promise.resolve(file);

        return loadImage(file).then(function (img) {
            var w = img.naturalWidth, h = img.naturalHeight;
            var scale = Math.min(1, MAX_SIDE / Math.max(w, h));
            var needsConvert = !/^image\/(jpeg|png)$/.test(file.type);
            if (scale === 1 && file.size < SKIP_BELOW && !needsConvert) return file;

            var canvas = document.createElement('canvas');
            canvas.width = Math.round(w * scale);
            canvas.height = Math.round(h * scale);
            var ctx = canvas.getContext('2d');
            ctx.fillStyle = '#fff'; // transparent PNG areas would otherwise turn black in JPEG
            ctx.fillRect(0, 0, canvas.width, canvas.height);
            ctx.drawImage(img, 0, 0, canvas.width, canvas.height);

            return new Promise(function (resolve) {
                canvas.toBlob(function (blob) {
                    if (!blob || (!needsConvert && blob.size >= file.size)) return resolve(file);
                    var name = file.name.replace(/\.[^.]*$/, '') + '.jpg';
                    resolve(new File([blob], name, { type: 'image/jpeg', lastModified: Date.now() }));
                }, 'image/jpeg', QUALITY);
            });
        }).catch(function () {
            return file;
        });
    }

    function setBusy(input, on) {
        busy += on ? 1 : -1;
        var status = input.parentNode.querySelector('.image-compress-status');
        if (!status) {
            status = document.createElement('div');
            status.className = 'image-compress-status';
            status.style.cssText = 'margin-top:6px;font-size:12px;color:#6b7280;';
            input.parentNode.insertBefore(status, input.nextSibling);
        }
        status.textContent = on ? 'Preparing photos... / Menyediakan gambar...' : '';
        status.hidden = !on;
    }

    // Capture phase on document runs before the input's own (inline or bound) change handlers.
    document.addEventListener('change', function (e) {
        var input = e.target;
        if (!input.matches || !input.matches('input[type=file][data-compress]')) return;
        if (input._compressedDispatch) { input._compressedDispatch = false; return; }
        if (!input.files || !input.files.length || typeof DataTransfer === 'undefined') return;

        e.stopImmediatePropagation();
        setBusy(input, true);

        Promise.all([].map.call(input.files, compress)).then(function (files) {
            var dt = new DataTransfer();
            files.forEach(function (f) { dt.items.add(f); });
            input.files = dt.files;
        }).finally(function () {
            setBusy(input, false);
            input._compressedDispatch = true;
            input.dispatchEvent(new Event('change', { bubbles: true }));
        });
    }, true);

    document.addEventListener('submit', function (e) {
        if (busy > 0) {
            e.preventDefault();
            e.stopImmediatePropagation();
            alert('Photos are still being prepared, please wait a moment and try again.\nGambar sedang disediakan, sila tunggu sebentar.');
        }
    }, true);
})();
