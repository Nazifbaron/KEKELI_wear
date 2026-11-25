(function () {
    function sync(slide) {
        var v = slide.querySelector('video.slide-video');
        if (!v) return;
        if (slide.classList.contains('hidden')) {
            v.pause();
            v.currentTime = 0;
        } else {
            var p = v.play();
            if (p && p.catch) p.catch(function () {});
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        var slides = document.querySelectorAll('#hero .slide');
        var obs = new MutationObserver(function (muts) {
            muts.forEach(function (m) { sync(m.target); });
        });
        slides.forEach(function (s) {
            sync(s);
            obs.observe(s, { attributes: true, attributeFilter: ['class'] });
        });
    });
})();
