/*
 * jDigiClock plugin 2.1.3
 *
 * http://www.radoslavdimov.com/jquery-plugins/jquery-plugin-digiclock/
 *
 * Copyright (c) 2009 Radoslav Dimov
 *
 * Dual licensed under the MIT and GPL licenses:
 * http://www.opensource.org/licenses/mit-license.php
 * http://www.gnu.org/licenses/gpl.html
 *
 * 17-NOV-2013:  2.1.1 Modified by Andrew Mercer to use offset from Server Time. Allows display of other timezones.
 * 08-FEB-2015:  2.1.2 Adapted to use Yahoo weather (AccuWeather not working) and JSONP (No proxy)
 * 21-MAR-2015:  2.1.3 Added .promise().done(function () {}) reference for compatibility with later jquery versions
 *                     Avoids repeated sliding panels on left/right clicks. Based on update by s4ty at
 *                     http://www.jquery-board.de/threads/3458-jDigiClock/page4
 *
 * WeatherLocationCodes now use WOEID codes, and query format using YQL:
 * https://developer.yahoo.com/yql/
 *
 * Easy lookup of WOEID codes at:
 * http://woeid.rosselliot.co.nz
 *
 * Dual licensed under the MIT and GPL licenses:
 * http://www.opensource.org/licenses/mit-license.php
 * http://www.gnu.org/licenses/gpl.html
 *
 */


(function($) {
    $.fn.extend({

        jdigiclock: function(options) {

            var defaults = {
                clockImagesPath: 'images/clock/',
                weatherImagesPath: 'images/weather/',
                /*imagesPath: 'images/',*/
                lang: 'en',
                am_pm: false,
                weatherLocationCode: '1100661',
                weatherMetric: 'c',
                weatherUpdate: 0,
                svrOffset: 0,
                proxyType: 'php'
            };

            var regional = [];
            regional['en'] = {
                monthNames: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
                dayNames: ['Dom', 'Lun', 'Mar', 'Mie', 'Jue', 'Vie', 'Sab']
            }


            var options = $.extend(defaults, options);

            return this.each(function() {

                var $this = $(this);
                var o = options;
                $this.imagesPath = o.imagesPath;
                $this.lang = regional[o.lang] == undefined ? regional['en'] : regional[o.lang];
                $this.am_pm = o.am_pm;
                $this.weatherLocationCode = o.weatherLocationCode;
                $this.weatherMetric = o.weatherMetric;
                $this.weatherUpdate = o.weatherUpdate;
                $this.svrOffset = o.svrOffset;
                $this.clockImagesPath = o.imagesPath + 'clock/';
                $this.weatherImagesPath = o.imagesPath + 'weather/';
                $this.currDate = '';
                $this.timeUpdate = '';


                var html = '<div id="plugin_container">';
                html    += '<p id="left_arrow"><img src="' + $this.imagesPath +'icon_left.png" /></p>';
                html    += '<p id="right_arrow"><img src="' + $this.imagesPath + 'icon_right.png" /></p>';
                html    += '<div id="digital_container">';
                html    += '<div id="clock"></div>';
                html    += '<div id="update"><img src="' + $this.imagesPath + 'refresh_red2.png" alt="reload" title="reload" id="reload" />' + $this.timeUpdate + '</div>';
                html    += '<div id="weather"></div>';
                html    += '</div>';
                html    += '<div id="forecast_container"></div>';
                html    += '</div>';

                $this.html(html);

                $this.displayClock($this);

                $this.displayWeather($this);

                var panel_pos = ($('#plugin_container > div').length - 1) * 246;
                var next = function() {
                    $('#right_arrow').unbind('click', next);
                    $('#plugin_container > div').filter(function(i) {
                        $(this).animate({'left': (i * 246) - 246 + 'px'}, 182, function() {
                            if (i == 0) {
                                $(this).appendTo('#plugin_container').css({'left':panel_pos + 'px'});
                            }
                        });
                    }).promise().done(function () {$('#right_arrow').bind('click', next);});
                };
                $('#right_arrow').bind('click', next);

                var prev = function() {
                    $('#left_arrow').unbind('click', prev);
                    $('#plugin_container > div:last').prependTo('#plugin_container').css({'left':'-246px'});
                    $('#plugin_container > div').filter(function(i) {
                        $(this).animate({'left': i * 246 + 'px'}, 182);
                    }).promise().done(function () {$('#left_arrow').bind('click', prev);});
                };
                $('#left_arrow').bind('click', prev);
            });
        }
    });


    $.fn.displayClock = function(el) {
        $.fn.getTime(el);
        setTimeout(function() {$.fn.displayClock(el)}, $.fn.delay());
    }

    $.fn.displayWeather = function(el) {
        $.fn.getWeather(el);
        if (el.weatherUpdate > 0) {
            setTimeout(function() {$.fn.displayWeather(el)}, (el.weatherUpdate * 60 * 1000));
        }
    }

    $.fn.delay = function() {
        var now = new Date();
        var delay = (60 - now.getSeconds()) * 1000;

        return delay;
    }

    $.fn.getTime = function(el) {
        var localtime = new Date();
        var now = new Date(localtime.getTime() - el.svrOffset);
        var old = new Date();
        old.setTime(now.getTime() - 60000);

        var now_hours, now_minutes, old_hours, old_minutes, timeOld = '';
        now_hours =  now.getHours();
        now_minutes = now.getMinutes();
        old_hours =  old.getHours();
        old_minutes = old.getMinutes();

        if (el.am_pm) {
            var am_pm = now_hours > 11 ? 'pm' : 'am';
            now_hours = ((now_hours > 12) ? now_hours - 12 : now_hours);
            old_hours = ((old_hours > 12) ? old_hours - 12 : old_hours);
        }

        now_hours   = ((now_hours <  10) ? "0" : "") + now_hours;
        now_minutes = ((now_minutes <  10) ? "0" : "") + now_minutes;
        old_hours   = ((old_hours <  10) ? "0" : "") + old_hours;
        old_minutes = ((old_minutes <  10) ? "0" : "") + old_minutes;
        // date
        el.currDate = el.lang.dayNames[now.getDay()] + ',&nbsp;' + now.getDate() + '&nbsp;' + el.lang.monthNames[now.getMonth()];
        // time update
        el.timeUpdate = el.currDate + ',&nbsp;' + now_hours + ':' + now_minutes;

        var firstHourDigit = old_hours.substr(0,1);
        var secondHourDigit = old_hours.substr(1,1);
        var firstMinuteDigit = old_minutes.substr(0,1);
        var secondMinuteDigit = old_minutes.substr(1,1);

        timeOld += '<div id="hours"><div class="line"></div>';
        timeOld += '<div id="hours_bg"><img src="' + el.clockImagesPath + 'clockbg1.png" /></div>';
        timeOld += '<img src="' + el.clockImagesPath + firstHourDigit + '.png" id="fhd" class="first_digit" />';
        timeOld += '<img src="' + el.clockImagesPath + secondHourDigit + '.png" id="shd" class="second_digit" />';
        timeOld += '</div>';
        timeOld += '<div id="minutes"><div class="line"></div>';
        if (el.am_pm) {
            timeOld += '<div id="am_pm"><img src="' + el.clockImagesPath + am_pm + '.png" /></div>';
        }
        timeOld += '<div id="minutes_bg"><img src="' + el.clockImagesPath + 'clockbg1.png" /></div>';
        timeOld += '<img src="' + el.clockImagesPath + firstMinuteDigit + '.png" id="fmd" class="first_digit" />';
        timeOld += '<img src="' + el.clockImagesPath + secondMinuteDigit + '.png" id="smd" class="second_digit" />';
        timeOld += '</div>';

        el.find('#clock').html(timeOld);

        // set minutes
        if (secondMinuteDigit != '9') {
            firstMinuteDigit = firstMinuteDigit + '1';
        }

        if (old_minutes == '59') {
            firstMinuteDigit = '511';
        }

        setTimeout(function() {
            $('#fmd').attr('src', el.clockImagesPath + firstMinuteDigit + '-1.png');
            $('#minutes_bg img').attr('src', el.clockImagesPath + 'clockbg2.png');
        },200);
        setTimeout(function() { $('#minutes_bg img').attr('src', el.clockImagesPath + 'clockbg3.png')},250);
        setTimeout(function() {
            $('#fmd').attr('src', el.clockImagesPath + firstMinuteDigit + '-2.png');
            $('#minutes_bg img').attr('src', el.clockImagesPath + 'clockbg4.png');
        },400);
        setTimeout(function() { $('#minutes_bg img').attr('src', el.clockImagesPath + 'clockbg5.png')},450);
        setTimeout(function() {
            $('#fmd').attr('src', el.clockImagesPath + firstMinuteDigit + '-3.png');
            $('#minutes_bg img').attr('src', el.clockImagesPath + 'clockbg6.png');
        },600);

        setTimeout(function() {
            $('#smd').attr('src', el.clockImagesPath + secondMinuteDigit + '-1.png');
            $('#minutes_bg img').attr('src', el.clockImagesPath + 'clockbg2.png');
        },200);
        setTimeout(function() { $('#minutes_bg img').attr('src', el.clockImagesPath + 'clockbg3.png')},250);
        setTimeout(function() {
            $('#smd').attr('src', el.clockImagesPath + secondMinuteDigit + '-2.png');
            $('#minutes_bg img').attr('src', el.clockImagesPath + 'clockbg4.png');
        },400);
        setTimeout(function() { $('#minutes_bg img').attr('src', el.clockImagesPath + 'clockbg5.png')},450);
        setTimeout(function() {
            $('#smd').attr('src', el.clockImagesPath + secondMinuteDigit + '-3.png');
            $('#minutes_bg img').attr('src', el.clockImagesPath + 'clockbg6.png');
        },600);

        setTimeout(function() {$('#fmd').attr('src', el.clockImagesPath + now_minutes.substr(0,1) + '.png')},800);
        setTimeout(function() {$('#smd').attr('src', el.clockImagesPath + now_minutes.substr(1,1) + '.png')},800);
        setTimeout(function() { $('#minutes_bg img').attr('src', el.clockImagesPath + 'clockbg1.png')},850);

        // set hours
        if (now_minutes == '00') {

            if (el.am_pm) {
                if (now_hours == '00') {
                    firstHourDigit = firstHourDigit + '1';
                    now_hours = '12';
                } else if (now_hours == '01') {
                    firstHourDigit = '001';
                    secondHourDigit = '111';
                } else {
                    firstHourDigit = firstHourDigit + '1';
                }
            } else {
                if (now_hours != '10') {
                    firstHourDigit = firstHourDigit + '1';
                }

                if (now_hours == '20') {
                    firstHourDigit = '1';
                }

                if (now_hours == '00') {
                    firstHourDigit = firstHourDigit + '1';
                    secondHourDigit = secondHourDigit + '11';
                }
            }

            setTimeout(function() {
                $('#fhd').attr('src', el.clockImagesPath + firstHourDigit + '-1.png');
                $('#hours_bg img').attr('src', el.clockImagesPath + 'clockbg2.png');
            },200);
            setTimeout(function() { $('#hours_bg img').attr('src', el.clockImagesPath + 'clockbg3.png')},250);
            setTimeout(function() {
                $('#fhd').attr('src', el.clockImagesPath + firstHourDigit + '-2.png');
                $('#hours_bg img').attr('src', el.clockImagesPath + 'clockbg4.png');
            },400);
            setTimeout(function() { $('#hours_bg img').attr('src', el.clockImagesPath + 'clockbg5.png')},450);
            setTimeout(function() {
                $('#fhd').attr('src', el.clockImagesPath + firstHourDigit + '-3.png');
                $('#hours_bg img').attr('src', el.clockImagesPath + 'clockbg6.png');
            },600);

            setTimeout(function() {
            $('#shd').attr('src', el.clockImagesPath + secondHourDigit + '-1.png');
            $('#hours_bg img').attr('src', el.clockImagesPath + 'clockbg2.png');
            },200);
            setTimeout(function() { $('#hours_bg img').attr('src', el.clockImagesPath + 'clockbg3.png')},250);
            setTimeout(function() {
                $('#shd').attr('src', el.clockImagesPath + secondHourDigit + '-2.png');
                $('#hours_bg img').attr('src', el.clockImagesPath + 'clockbg4.png');
            },400);
            setTimeout(function() { $('#hours_bg img').attr('src', el.clockImagesPath + 'clockbg5.png')},450);
            setTimeout(function() {
                $('#shd').attr('src', el.clockImagesPath + secondHourDigit + '-3.png');
                $('#hours_bg img').attr('src', el.clockImagesPath + 'clockbg6.png');
            },600);

            setTimeout(function() {$('#fhd').attr('src', el.clockImagesPath + now_hours.substr(0,1) + '.png')},800);
            setTimeout(function() {$('#shd').attr('src', el.clockImagesPath + now_hours.substr(1,1) + '.png')},800);
            setTimeout(function() { $('#hours_bg img').attr('src', el.clockImagesPath + 'clockbg1.png')},850);
        }
    }

 $.fn.getWeather = function(el) {

     el.find('#weather').html('<p class="loading">Actualizando Clima ...</p>');
     el.find('#forecast_container').html('<p class="loading">Actualizando Clima ...</p>');

     $.getJSON('https://query.yahooapis.com/v1/public/yql?q=select%20*%20from%20weather.forecast%20where%20'
                  + 'woeid=' + el.weatherLocationCode
                  + '%20and%20u="' + el.weatherMetric + '"'
                  + '&format=json&diagnostics=true&env=store%3A%2F%2Fdatatables.org%2Falltableswithkeys&callback=?',
             function (data) {

                el.find('#weather .loading, #forecast_container .loading').hide();

                var curr_temp = '<p class="temp">' + data.query.results.channel.item.condition.temp
                               + '&deg;<span class="metric">'
                               + el.weatherMetric.toUpperCase() + '</span></p>';

                 el.find('#weather').css('background','url('
                         + el.weatherImagesPath
                         + 'yw'
                         + data.query.results.channel.item.condition.code
                         + '.png) 50% 100% no-repeat');

                 var condicion = data.query.results.channel.item.condition.text;
                 var newcondicion = "";

                 if (condicion == "Sunny")
                 {
                     newcondicion = "Soleado";
                 }
                 else if (condicion == "Cloudy")
                 {
                     newcondicion = "Nublado";
                 }
                 else if (condicion == "Rain")
                 {
                     newcondicion = "Lluvia";
                 }
                 else if (condicion == "Scattered Showers")
                 {
                     newcondicion = "Chubascos dispersos";
                 }
                 else if (condicion == "Thunderstorms")
                 {
                     newcondicion = "Tormentas eléctricas";
                 }
                 else if (condicion == "Scattered Thunderstorms")
                 {
                     newcondicion = "Tormentas eléctricas dispersas";
                 }
                 else if (condicion == "Breezy")
                 {
                     newcondicion = "Ventoso";
                 }
                 else if (condicion == "Mostly Cloudy")
                 {
                     newcondicion = "Mayormente nublado";
                 }
                 else if (condicion == "Partly Cloudy")
                 {
                     newcondicion = "Parcialmente nublado";
                 }
                 else if (condicion == "Mostly Sunny")
                 {
                     newcondicion = "Mayormente soleado";
                 }
                 else if (condicion == "Snow Showers")
                 {
                     newcondicion = "Chubascos de nieve";
                 }
                 else if (condicion == "Scattered Snow Showers")
                 {
                     newcondicion = "Chubascos de nieve dispersos";
                 }
                 else if (condicion == "Rain And Snow")
                 {
                     newcondicion = "Lluvia y nieve";
                 }
                 else if (condicion == "Mostly Clear")
                 {
                     newcondicion = "Mayormente claro";
                 }


                 var weather = '<div id="local"><p class="city">'
                             + data.query.results.channel.location.city
                             + '</p><p class="currdesc">'
                             + newcondicion
                             + '</p></div>';

                 weather += '<div id="temp"><p id="date">'
                         + el.currDate
                         + '</p>'
                         + curr_temp + '</div>';

                 el.find('#weather').html(weather);

                 // forecast
                 el.find('#forecast_container').append('<div id="current"></div>');

                 var curr_for = curr_temp + '<p class="high_low">'
                              + data.query.results.channel.item.forecast[0].high
                              + '&deg;&nbsp;/&nbsp;'
                              + data.query.results.channel.item.forecast[0].low
                              + '&deg;</p>';

                 curr_for    += '<p class="city">'
                              + data.query.results.channel.location.city
                              + '</p>';

                 var condicion2 = data.query.results.channel.item.forecast[0].text;
                 var newcondicion2 = "";

                 if (condicion2 == "Sunny")
                 {
                     newcondicion2 = "Soleado";
                 }
                 else if (condicion2 == "Cloudy")
                 {
                     newcondicion2 = "Nublado";
                 }
                 else if (condicion2 == "Rain")
                 {
                     newcondicion2 = "Lluvia";
                 }
                 else if (condicion2 == "Scattered Showers")
                 {
                     newcondicion2 = "Chubascos dispersos";
                 }
                 else if (condicion2 == "Thunderstorms")
                 {
                     newcondicion2 = "Tormentas eléctricas";
                 }
                 else if (condicion2 == "Scattered Thunderstorms")
                 {
                     newcondicion2 = "Tormentas eléctricas dispersas";
                 }
                 else if (condicion2 == "Breezy")
                 {
                     newcondicion2 = "Ventoso";
                 }
                 else if (condicion2 == "Mostly Cloudy")
                 {
                     newcondicion2 = "Mayormente nublado";
                 }
                 else if (condicion2 == "Partly Cloudy")
                 {
                     newcondicion2 = "Parcialmente nublado";
                 }
                 else if (condicion2 == "Mostly Sunny")
                 {
                     newcondicion2 = "Mayormente soleado";
                 }
                 else if (condicion2 == "Snow Showers")
                 {
                     newcondicion2 = "Chubascos de nieve";
                 }
                 else if (condicion2 == "Scattered Snow Showers")
                 {
                     newcondicion2 = "Chubascos de nieve dispersos";
                 }
                 else if (condicion2 == "Rain And Snow")
                 {
                     newcondicion2 = "Lluvia y nieve";
                 }
                 else if (condicion2 == "Mostly Clear")
                 {
                     newcondicion2 = "Mayormente claro";
                 }


                 curr_for    += '<p class="text">'
                             + newcondicion2
                             + '</p>';

                 el.find('#current').css('background','url('
                        + el.weatherImagesPath
                        + 'yw'
                        + data.query.results.channel.item.forecast[0].code
                        + '.png) 50% 0 no-repeat').append(curr_for);

                 el.find('#forecast_container').append('<ul id="forecast"></ul>');
                 data.query.results.channel.item.forecast.shift();
                 for (var i in data.query.results.channel.item.forecast) {
                     var d_date = new Date(data.query.results.channel.item.forecast[i].date);

                     var day_name2 = "";
                     var day_name = data.query.results.channel.item.forecast[i].day;

                     if (day_name == "Mon"){
                         day_name2 = "Lun";
                     }
                     else if (day_name == "Tue"){
                         day_name2 = "Mar";
                     }
                     else if (day_name == "Wed"){
                         day_name2 = "Mie";
                     }
                     else if (day_name == "Thu"){
                         day_name2 = "Jue";
                     }
                     else if (day_name == "Fri"){
                         day_name2 = "Vie";
                     }
                     else if (day_name == "Sat"){
                         day_name2 = "Sab";
                     }
                     else if (day_name == "Sun"){
                         day_name2 = "Dom";
                     }

                     var forecast = '<li>';

                     forecast    += '<p>' + day_name2 + '</p>';
                     forecast    += '<img src="'
                                  + el.weatherImagesPath
                                  + 'yw'
                                  + data.query.results.channel.item.forecast[i].code
                                  + '.png" alt="'
                                  + data.query.results.channel.item.forecast[i].text
                                  + '" title="'
                                  + data.query.results.channel.item.forecast[i].text
                                  + '" />';

                     forecast    += '<p>'
                                  + data.query.results.channel.item.forecast[i].high
                                  + '&deg;&nbsp;/&nbsp;'
                                  + data.query.results.channel.item.forecast[i].low
                                  + '&deg;</p>';

                     forecast    += '</li>';
                     el.find('#forecast').append(forecast);
                 }

                 el.find('#forecast_container').append('<div id="update"><img src="' + el.imagesPath + 'refresh_01.png" alt="reload" title="reload" id="reload" />' + el.timeUpdate + '</div>');

                 $('#reload').click(function() {
                     el.find('#weather').html('');
                     el.find('#forecast_container').html('');
                     $.fn.getWeather(el);
                 });

        });
    }
})(jQuery);
