(function ($) {
    'use strict';

    if (!th_advance_product_search_options.tapsp_enable_voice_search) return;

    var SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;

    if (!SpeechRecognition) {
        // Browser does not support voice search — hide buttons
        $(document).ready(function () {
            $('.tapsp-voice-btn').hide();
        });
        return;
    }

    $(document).ready(function () {

        $('.tapsp-voice-btn').each(function () {
            var $btn = $(this);
            var $wrap = $btn.closest('.thaps-from-wrap');
            var $input = $wrap.find('.thaps-search-autocomplete');

            var recognition = new SpeechRecognition();
            recognition.continuous = false;
            recognition.interimResults = false;
            recognition.lang = document.documentElement.lang || 'en-US';

            $btn.on('click', function (e) {
                e.preventDefault();
                e.stopPropagation();

                if ($btn.hasClass('tapsp-voice-listening')) {
                    recognition.stop();
                    return;
                }

                try {
                    recognition.start();
                    $btn.addClass('tapsp-voice-listening');
                } catch (err) {
                    $btn.removeClass('tapsp-voice-listening');
                }
            });

            recognition.onresult = function (event) {
                var transcript = event.results[0][0].transcript;
                $input.val(transcript).trigger('input').trigger('keyup');
                $btn.removeClass('tapsp-voice-listening');
            };

            recognition.onerror = function () {
                $btn.removeClass('tapsp-voice-listening');
            };

            recognition.onend = function () {
                $btn.removeClass('tapsp-voice-listening');
            };
        });

    });

})(jQuery);
