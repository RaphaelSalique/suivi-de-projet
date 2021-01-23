document.addEventListener('DOMContentLoaded', function(event) {
    function getAll(selector) {
        var parent = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : document;

        return Array.prototype.slice.call(parent.querySelectorAll(selector), 0);
    }
    // galerie : afficher / masquer
    var $galeries = getAll('.showhidedisplayer');

    if ($galeries.length > 0) {
        $galeries.forEach(function ($el) {
            $el.addEventListener('click', function (event) {
                event.stopPropagation();
                $el.parentElement.querySelector('.showhidedisplayed').classList.toggle('is-hidden');
                let $i = $el.querySelector('i.fa-arrow')
                if ($i) {
                    $i.classList.toggle('fa-arrow-right')
                    $i.classList.toggle('fa-arrow-down')
                }
            });
        });
    }

    // dropdown
    var $dropdowns = getAll('.dropdown:not(.is-hoverable)');

    if ($dropdowns.length > 0) {
        $dropdowns.forEach(function ($el) {
            $el.addEventListener('click', function (event) {
                event.stopPropagation();
                $el.classList.toggle('is-active');
            });
        });

        document.addEventListener('click', function (event) {
            closeDropdowns();
        });
    }

    function closeDropdowns() {
        $dropdowns.forEach(function ($el) {
            $el.classList.remove('is-active');
        });
    }
    // modal (thumbnails)
    var rootEl = document.documentElement;
    var $modals = getAll('.modal');
    var $modalButtons = getAll('.modal-button');
    var $modalCloses = getAll('.modal-background, .modal-close, .modal-card-head .delete, .modal-card-foot .button');

    if ($modalButtons.length > 0) {
        $modalButtons.forEach(function ($el) {
            $el.addEventListener('click', function () {
                var target = $el.dataset.target;
                openModal(target);
            });
        });
    }

    if ($modalCloses.length > 0) {
        $modalCloses.forEach(function ($el) {
            $el.addEventListener('click', function () {
                closeModals();
            });
        });
    }

    function openModal(target) {
        var $target = document.getElementById(target);
        rootEl.classList.add('is-clipped');
        $target.classList.add('is-active');
    }

    function closeModals() {
        rootEl.classList.remove('is-clipped');
        $modals.forEach(function ($el) {
            $el.classList.remove('is-active');
        });
    }

    document.addEventListener('keydown', function (event) {
        var e = event || window.event;
        if (e.keyCode === 27) {
            closeModals();
            closeDropdowns();
        }
    });
})
