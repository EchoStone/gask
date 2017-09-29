/**
 * Created by Administrator on 2015/3/6.
 */
;
(function ($) {
    var xcDialog = {
        alert: function (options) {
            if ($('body').find('.xcDialog')) {
                $('.xcDialog').remove();
            }

            var defaults = {
                content: "", timeOut: 3000, onClose: function () {
                }
            };
            options = $.extend(true, {}, defaults, options);

            //处理相应的对话框
            var panel = '<div class="xcDialog"> <div class="alert">' + options.content + '</div></div>';
           
            $('body').append(panel);

            var _left = ($(window).width() - $(".xcDialog .alert").width())/2;
            $(".xcDialog .alert").css('marginLeft', _left);

            var tid = setTimeout(function () {
                $(".xcDialog .alert").hide();
                if(options.onClose) {
                    options.onClose();
                }
            }, options.timeOut);

        }
    };

    $.xcDialog = xcDialog;
})(Zepto);
