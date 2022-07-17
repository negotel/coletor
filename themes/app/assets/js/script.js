'use strict';

function myChecked() {
    var checkBoxs = document.getElementById("cc-1");
    if (checkBoxs.checked == false) {
        localStorage.setItem('modalNaoMostra', true)
    } else {
        localStorage.setItem('modalNaoMostra', false)
    }
}

app.config({

    /*
    |--------------------------------------------------------------------------
    | Autoload
    |--------------------------------------------------------------------------
    |
    | By default, the app will load all the required plugins from /assets/vendor/
    | directory. If you need to disable this functionality, simply change the
    | following variable to false. In that case, you need to take care of loading
    | the required CSS and JS files into your page.
    |
    */

    autoload: true,

    /*
    |--------------------------------------------------------------------------
    | Provide
    |--------------------------------------------------------------------------
    |
    | Specify an array of the name of vendors that should be load in all pages.
    | Visit following URL to see a list of available vendors.
    |
    | https://thetheme.io/theadmin/help/article-dependency-injection.html#provider-list
    |
    */

    provide: ['typeahead'],

    /*
    |--------------------------------------------------------------------------
    | Google API Key
    |--------------------------------------------------------------------------
    |
    | Here you may specify your Google API key if you need to use Google Maps
    | in your application
    |
    | Warning: You should replace the following value with your own Api Key.
    | Since this is our own API Key, we can't guarantee that this value always
    | works for you.
    |
    | https://developers.google.com/maps/documentation/javascript/get-api-key
    |
    */

    googleApiKey: 'AIzaSyDRBLFOTTh2NFM93HpUA4ZrA99yKnCAsto',

    /*
    |--------------------------------------------------------------------------
    | Google Analytics Tracking
    |--------------------------------------------------------------------------
    |
    | If you want to use Google Analytics, you can specify your Tracking ID in
    | this option. Your key would be a value like: UA-12345678-9
    |
    */

    googleAnalyticsId: '',

    /*
    |--------------------------------------------------------------------------
    | Smooth Scroll
    |--------------------------------------------------------------------------
    |
    | By changing the value of this option to true, the browser's scrollbar
    | moves smoothly on scroll.
    |
    */

    smoothScroll: false,

    /*
    |--------------------------------------------------------------------------
    | Save States
    |--------------------------------------------------------------------------
    |
    | If you turn on this option, we save the state of your application to load
    | them on the next visit (e.g. make topbar fixed).
    |
    | Supported states: Topbar fix, Sidebar fold
    |
    */

    saveState: false,

    /*
    |--------------------------------------------------------------------------
    | Cache Bust String
    |--------------------------------------------------------------------------
    |
    | Adds a cache-busting string to the end of a script URL. We automatically
    | add a question mark (?) before the string. Possible values are: '1.2.3',
    | 'v1.2.3', or '123456789'
    |
    */

    cacheBust: '',


});


/*
|--------------------------------------------------------------------------
| Application Is Ready
|--------------------------------------------------------------------------
|
| When all the dependencies of the page are loaded and executed,
| the application automatically call this function. You can consider it as
| a replacer for jQuery ready function - "$( document ).ready()".
|
*/
app.ready(function() {

    var verModalDeAviso = localStorage.getItem("modalNaoMostra");
    let urlaction = document.querySelector('input#url-action');

    if (urlaction) {
        ready_statistic({
            'search': false,
            'action': document.querySelector('input#url-action').value,
            'vmonth': document.querySelector('input#vmonth').value
        });
    }

    $("input[type=file]").change(function(e) {
        var file = this;
        if (file.files && file.files[0]) {
            var render = new FileReader();
            render.onload = function(e) {
                $(".j_profile_image").fadeTo(100, 0.1, function() {
                    $(".j_profile_image").attr("src", e.target.result).fadeTo(100, 1);
                });
            };
            render.readAsDataURL(file.files[0]);
        }
    });

    $("input[name=tipo]").click(function(e) {
        if (this.value == 'controle') {
            $("input[name=milhar]").attr("placeholder", "Digite o número do controle...");
        } else {
            $("input[name=milhar]").attr("placeholder", "Digite o número da milhar...");
        }
    })

    $("#consulta-dinamica").on("click", function(e) {
        e.preventDefault();
        var load = $(".ajax_load");
        var flashClass = "ajax_response";
        var flash = $("." + flashClass);

        $.ajax({
            url: $(this).data().action + "?" + $("#milhar").serialize() + "&" + $("#id_premio").serialize() + "&" + $("input[name='tipo'").serialize(),
            type: "POST",
            dataType: "json",
            beforeSend: function() {
                $(".alert").hide();
                load.fadeIn(200).css("display", "flex");
            },
            success: function(response) {

                //reload
                if (response.reload) {
                    window.location.reload();
                } else {
                    load.fadeOut(200);
                }

                //message
                if (response.message) {
                    if (flash.length) {
                        flash.html(response.message).fadeIn(100).effect("bounce", 300);
                    } else {
                        form.prepend("<div class='" + flashClass + "'>" + response.message + "</div>")
                            .find("." + flashClass).effect("bounce", 300);
                    }
                } else {
                    flash.fadeOut(100);
                }


                if (response.data) {
                    load.fadeOut(200);
                    $("#result-consult").html(response.data);
                }
            }
        });
    });


    $("form:not('.ajax_off')").submit(function(e) {
        e.preventDefault();
        var form = $(this);
        var load = $(".ajax_load");
        var flashClass = "ajax_response";
        var flash = $("." + flashClass);

        var data = form.data();

        form.ajaxSubmit({
            url: form.attr("action"),
            type: "POST",
            dataType: "json",
            beforeSend: function() {
                $(".alert").hide();
                if (data.ajax_load === true) {
                    $(".ajax_load")
                        .fadeIn(200)
                        .css("display", "flex")
                        .find(".ajax_load_box_title")
                        .text(data.message);
                } else {
                    load.fadeIn(200).css("display", "flex");
                }
            },
            uploadProgress: function(event, position, total, completed) {
                var loaded = completed;
                var load_title = $(".ajax_load_box_title");
                load_title.text("Enviando (" + loaded + "%)");

                form.find("input[type='file']").val(null);
                if (completed >= 100) {
                    load_title.text("Aguarde, carregando...");
                }
            },
            success: function(response) {
                //redirect
                if (response.redirect) {
                    window.location.href = response.redirect;
                } else {
                    load.fadeOut(200);
                }

                //reload
                if (response.reload) {
                    window.location.reload();
                } else {
                    load.fadeOut(200);
                }

                if (response.reset) {
                    form.trigger("reset");
                }

                //message
                if (response.message) {
                    if (flash.length) {
                        flash.html(response.message).fadeIn(100).effect("bounce", 300);
                    } else {
                        form.prepend("<div class='" + flashClass + "'>" + response.message + "</div>").find("." + flashClass).effect("bounce", 300);
                    }
                } else {
                    flash.fadeOut(100);
                }
            },
            complete: function() {
                if (form.data("reset") === true) {
                    form.trigger("reset");
                }
            },
            error: function() {
                var message = "<div class='message error icon-warning'>Desculpe mas não foi possível processar a requisição. Favor tente novamente!</div>";

                if (response.reset) {
                    form.trigger("reset");
                }

                if (flash.length) {
                    flash.html(message).fadeIn(100).effect("bounce", 300);
                } else {
                    form.prepend("<div class='" + flashClass + "'>" + message + "</div>").find("." + flashClass).effect("bounce", 300);
                }
                load.fadeOut(200);
            }
        });
    });

    //editables 
    $('#username').editable({
        url: '/post',
        type: 'text',
        pk: 1,
        name: 'username',
        title: 'Enter username'
    });

    $("[data-confirm-transference]").click(function(event) {

        event.preventDefault();
        let clicked = $(this);
        let data = clicked.data();
        let load = $(".ajax_load");

        swal({
            title: "Confirmação de Transferencia",
            text: 'para transferir este objeto informe o numero da remessa no qual que transferir',
            input: 'text',
            showCancelButton: true,
            confirmButtonText: 'Confirmar',
            inputPlaceholder: 'Numero da Remessa',
            inputValidator: (value) => {
                if (!value) {
                    return 'Informe o numero da remessa que deseja transferir'
                }
            }
        }).then(function(e) {
            console.log(e);
            if (e.value) {
                $.ajax({
                    url: data.action,
                    type: "POST",
                    data: e,
                    dataType: "json",
                    beforeSend: function() {
                        $(".ajax_load")
                            .fadeIn(200)
                            .css("display", "flex")
                            .find(".ajax_load_box_title")
                            .text('Aguarde, processando dados...');
                    },
                    success: function(response) {
                        load.fadeOut(200);
                        if (response.result) {
                            toast('success', response.message);
                            return false;
                        }


                        //reload
                        if (response.reload) {
                            setTimeout(function() {
                                window.location.reload();
                            }, 500)
                        } else {
                            load.fadeOut(200);
                        }

                        toast(response.type, response.message);
                    },
                    error: function() {
                        toast('error', 'Ops, algo de errado aconteceu ao execulta dados.');
                        load.fadeOut();
                    }
                });
                return false;
            }

            if (e.dismiss == 'cancel') {
                abrirModal(data.urlPrint);
            }
            return false;
        }, function(dismiss) {
            load.fadeOut(200);
        })
    });


    $("[data-confirm-cancel]").click(function(e) {

        e.preventDefault();
        let clicked = $(this);
        let data = clicked.data();
        let load = $(".ajax_load");

        let title = (data.title == undefined || data.title == "" ? 'Modal sem Titutlo' : data.title);

        app.modaler({
            url: data.action,
            type: 'top',
            title: title,
            cancelVisible: true,
            cancelText: 'Cancelar',
            confirmText: 'Confirmar',
            onConfirm: function(modal) {
                let observacoes_cancelamento = document.querySelector('textarea');
                console.log(modal);
                if (observacoes_cancelamento.value == undefined || observacoes_cancelamento.value == "") {
                    toast('error', 'Informe o motivo do cancelamento');
                    return false;
                }

                console.log(data);

                let object = data;
                let object1 = { 'observacoes_cancelamento': observacoes_cancelamento.value };
                let data_set = Object.assign(object, object1);

                $.ajax({
                    url: data.action,
                    type: "POST",
                    data: data_set,
                    dataType: "json",
                    beforeSend: function() {
                        $(".ajax_load")
                            .fadeIn(200)
                            .css("display", "flex")
                            .find(".ajax_load_box_title")
                            .text('Aguarde, processando dados...');
                    },
                    success: function(response) {
                        load.fadeOut(200);

                        if (response.result) {
                            toast('success', response.message);
                            return false;
                        }


                        //reload
                        if (response.reload) {
                            setTimeout(function() {
                                window.location.reload();
                            }, 500)
                        } else {
                            load.fadeOut(200);
                        }

                        toast(response.type, response.message);
                    },
                    error: function() {
                        toast('error', 'Ops, algo de errado aconteceu ao execulta dados.');
                        load.fadeOut();
                    }
                });
                return false;
            }
        });
    });
    
    $("[data-modal-confirm]").click(function(e) {

        e.preventDefault();
        let clicked = $(this);
        let data = clicked.data();
        let load = $(".ajax_load");

        swal({
            title: "Confirmação de Impressão",
            text: "Você deseja imprimir e finalizar esta remessa?",
            type: 'info',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sim, Finalizar e Imprimir!',
            cancelButtonText: 'Não, Apenas Imprimir!',
            confirmButtonClass: 'btn btn-success',
            cancelButtonClass: 'btn btn-info',
            buttonsStyling: false
        }).then(function(e) {

            if (e.value) {
                $.ajax({
                    url: data.action,
                    type: "POST",
                    data: data,
                    dataType: "json",
                    beforeSend: function() {
                        $(".ajax_load")
                            .fadeIn(200)
                            .css("display", "flex")
                            .find(".ajax_load_box_title")
                            .text('Aguarde, processando dados...');
                    },
                    success: function(response) {
                        load.fadeOut(200);
                        if (response.result) {
                            toast('success', response.message);
                            abrirModal(data.urlPrint);
                            return false;
                        }

                        toast('error', response.message);
                    },
                    error: function() {
                        toast('error', 'Ops, algo de errado aconteceu ao execulta dados.');
                        load.fadeOut();
                    }
                });
                return false;
            }

            if (e.dismiss == 'cancel') {
                abrirModal(data.urlPrint);
            }
            return false;
        }, function(dismiss) {
            load.fadeOut(200);
        })
    });


    $("[data-smonth]").click(function(e) {
        var clicked = $(this);
        var data = clicked.data();

        let labels = [];
        let datasets = [];
        let dataset = [];

        ready_statistic(data);

        $.ajax({
            url: data.action,
            type: "POST",
            dataType: "json",
            data: data,
            beforeSend: function() {
                $(".ajax_load")
                    .fadeIn(200)
                    .css("display", "flex")
                    .find(".ajax_load_box_title")
                    .text("Aguarde, carregando dashboard...");
            },
            success: function(response) {

                let valores_coletados = [];
                let valores_pendentes = [];

                for (let i in response['coletados'][data.vmonth][0]) {
                    valores_coletados.push(response['coletados'][data.vmonth][0][i]);
                }

                for (let i in response['pendentes'][data.vmonth][0]) {
                    valores_pendentes.push(response['pendentes'][data.vmonth][0][i]);
                }


                new Chart($("#chart-line-5"), {
                    type: 'bar',
                    data: {
                        labels: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31],
                        datasets: [{
                            label: "Coletados",
                            fill: false,
                            borderWidth: 3,
                            pointRadius: 4,
                            borderColor: "#36a2eb",
                            backgroundColor: "#36a2eb",
                            pointBackgroundColor: "#36a2eb",
                            pointBorderColor: "#36a2eb",
                            pointHoverBackgroundColor: "#fff",
                            pointHoverBorderColor: "#36a2eb",
                            data: valores_coletados
                        }, {
                            label: "Pedentes",
                            fill: false,
                            borderWidth: 3,
                            pointRadius: 4,
                            borderColor: "#ff6384",
                            backgroundColor: "#ff6384",
                            pointBackgroundColor: "#ff6384",
                            pointBorderColor: "#ff6384",
                            pointHoverBackgroundColor: "#fff",
                            pointHoverBorderColor: "#ff6384",
                            data: valores_pendentes
                        }]
                    },
                    options: {}
                });

                document.querySelector('span#total').textContent = response['count_remessas'];
                document.querySelector('span#total-collection').textContent = response['count_item_coletados'];
                document.querySelector('span#total-open').textContent = response['count_item_abertos'];
                $(".ajax_load").fadeOut(200);

            },
            error: function() {
                $(".ajax_load").fadeOut();
            }
        });
    })

    $("[data-post]").click(function(e) {
        e.preventDefault();

        var clicked = $(this);
        var data = clicked.data();
        var load = $(".ajax_load");
        swal({
            text: data.confirm,
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sim,Excluir!',
            cancelButtonText: 'Não, Cancele!',
            confirmButtonClass: 'btn btn-primary',
            cancelButtonClass: 'btn btn-secondary',
            buttonsStyling: false
        }).then(function(e) {
            if (e.value) {
                $.ajax({
                    url: data.post,
                    type: "POST",
                    data: data,
                    dataType: "json",
                    beforeSend: function() {
                        if (data.ajax_load === true) {
                            $(".ajax_load")
                                .fadeIn(200)
                                .css("display", "flex")
                                .find(".ajax_load_box_title")
                                .text(data.message);
                        } else {
                            load.fadeIn(200).css("display", "flex");
                        }
                    },
                    success: function(response) {
                        //redirect
                        if (response.redirect) {
                            window.location.href = response.redirect;
                        } else {
                            load.fadeOut(200);
                        }

                        //reload
                        if (response.reload) {
                            window.location.reload();
                        } else {
                            load.fadeOut(200);
                        }

                        //message
                        if (response.message) {
                            ajaxMessage(response.message, ajaxResponseBaseTime);
                        }
                    },
                    error: function() {
                        ajaxMessage(ajaxResponseRequestError, 5);
                        load.fadeOut();
                    }
                });
            }

            return false;
        }, function(dismiss) {
            load.fadeOut(200);
        })
    });

    $('[data-typeahead]').keyup(function(event) {
        event.preventDefault();
        var urlAction = $(this).data("url");

        $('[data-typeahead]').on("keydown", function(event) {
            if (event.keyCode === $.ui.keyCode.TAB && $(this).autocomplete("instance").menu.active) {
                event.preventDefault();
            }
        }).autocomplete({
            source: function(request, response) {
                $.getJSON(urlAction, {
                    term: extractLast(request.term)
                }, response);
            },
            search: function() {
                var term = extractLast(this.value);
                if (term.length < 2) {
                    return false;
                }
            },
            focus: function(event, ui) {
                return false;
            },
            select: function(event, ui) {
                var terms = split(this.value);
                terms.pop();
                terms.push(ui.item.value);
                $("input[name=" + $(this).data("id") + "]").val(ui.item.id);
            }
        });
    });

    function split(val) {
        return val.split(/;\s*/);
    }

    function extractLast(term) {
        return split(term).pop();
    }


    function ready_statistic(data = null) {

        let labels = [];
        let datasets = [];
        let dataset = [];

        $.ajax({
            url: data.action,
            type: "POST",
            dataType: "json",
            data: data,
            beforeSend: function() {
                $(".ajax_load")
                    .fadeIn(200)
                    .css("display", "flex")
                    .find(".ajax_load_box_title")
                    .text("Aguarde, carregando dashboard...");
            },
            success: function(response) {

                let valores_coletados = [];
                let valores_pendentes = [];
                let dataset = [];

                for (let i in response['coletados'][data.vmonth][0]) {
                    valores_coletados.push(response['coletados'][data.vmonth][0][i]);
                }

                for (let i in response['pendentes'][data.vmonth][0]) {
                    valores_pendentes.push(response['pendentes'][data.vmonth][0][i]);
                }


                new Chart($("#chart-line-5"), {
                    type: 'bar',
                    data: {
                        labels: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31],
                        datasets: [{
                            label: "Coletados",
                            borderColor: "#36a2eb",
                            backgroundColor: "#36a2eb",
                            pointBackgroundColor: "#36a2eb",
                            pointBorderColor: "#36a2eb",
                            pointHoverBackgroundColor: "#fff",
                            pointHoverBorderColor: "#36a2eb",
                            data: valores_coletados
                        }, {
                            label: "Pedentes",
                            borderColor: "#ff6384",
                            backgroundColor: "#ff6384",
                            pointBackgroundColor: "#ff6384",
                            pointBorderColor: "#ff6384",
                            pointHoverBackgroundColor: "#fff",
                            pointHoverBorderColor: "#ff6384",
                            data: valores_pendentes
                        }]
                    },
                    options: {
                        scales: {
                            yAxes: [{
                                ticks: {
                                    beginAtZero: true
                                }
                            }]
                        }
                    }
                });

                $('span#total').removeClass('total');
                $('span#total-collection').removeClass('total');
                $('span#total-open').removeClass('total');
                $('span#total-posted').removeClass('total');

                document.querySelector('span#total').textContent = response['count_remessas'];
                document.querySelector('span#total-collection').textContent = response['count_item_coletados'];
                document.querySelector('span#total-open').textContent = response['count_item_abertos'];
                document.querySelector('span#total-posted').textContent = 0;
                $(".card-loading").css("display", "none");
                $(".ajax_load").fadeOut(200);

                if (verModalDeAviso !== "true") {
                    $('#modal-center').modal('show');
                }
            },
            error: function() {
                $(".ajax_load").fadeOut();
            }
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Plugins
    |--------------------------------------------------------------------------
    |
    | Import initialization of plugins that used in your application
    |
    */

    /*
     * Search in Theadmin components
     */
    if (window["Bloodhound"]) {
        var theadminComponents = new Bloodhound({
            datumTokenizer: Bloodhound.tokenizers.obj.whitespace('tokens'),
            queryTokenizer: Bloodhound.tokenizers.whitespace,
            prefetch: {
                url: app.dir.assets + 'data/json/files.json',
                cache: false
            }
        });

        $('#theadmin-search input').typeahead(null, {
            name: 'theadmin-components',
            display: 'title',
            source: theadminComponents,
            templates: {
                suggestion: function(data) {
                    return '<a href="' + location.origin + '/' + data.url + '"><h6 class="mb-1">' + data.title + '</h6><small>' + data.description + '</small></a>';
                }
            }
        });

        $('#theadmin-search input').bind('typeahead:select', function(ev, data) {
            window.location.href = location.origin + '/' + data.url;
        });

        $('#theadmin-search input').bind('typeahead:open', function(ev, data) {
            $(this).closest('#theadmin-search').find('.lookup-placeholder span').css('opacity', '0');
        });

        $('#theadmin-search input').bind('typeahead:close', function(ev, data) {
            if ($(this).val() == "") {
                $(this).closest('#theadmin-search').find('.lookup-placeholder span').css('opacity', '1');
            }
        });
    }


    /*
    |--------------------------------------------------------------------------
    | Paritials
    |--------------------------------------------------------------------------
    |
    | Import your main application code
    |
    */
    /*
     * Display a warning when the page opened using "file" protocol
     */
    if (location.protocol == 'file:') {
        app.toast('Please open the page using "http" protocol for full functionality.', {
            duration: 15000,
            actionTitle: 'Read more',
            actionUrl: ''
        })
    }
    /*
    |--------------------------------------------------------------------------
    | Color Changer
    |--------------------------------------------------------------------------
    |
    | This is a tiny code to implement color changer for our demonstrations.
    |
    */
    var demo_colors = ['primary', 'secondary', 'success', 'info', 'warning', 'danger', 'purple', 'pink', 'cyan', 'yellow', 'brown', 'dark'];

    /*
     * Color changer using base pallet name
     */
    $('[data-provide~="demo-color-changer"]').each(function() {
        var target = $(this).data('target'),
            baseClass = $(this).data('base-class'),
            html = '',
            name = $(this).dataAttr('name', ''),
            checked = $(this).dataAttr('checked', ''),
            exclude = $(this).dataAttr('exclude', ''),
            prefix = '';

        if ($(this).hasDataAttr('pale')) {
            prefix = 'pale-';
        }

        if (name == '') {
            name = Math.random().toString(36).replace(/[^a-z]+/g, '').substr(0, 5);
        }

        html = '<div class="color-selector color-selector-sm">';

        $.each(demo_colors, function(i, key) {

            // Check if we need to exclude any code
            if (exclude.indexOf(key) > -1) {
                return;
            }

            var color = prefix + key;
            html += '<label' + (prefix === 'pale-' ? ' class="inverse"' : '') + '><input type="radio" value="' +
                color + '" name="' + name + '"' + (checked === key ? ' checked' : '') + '><span class="bg-' +
                color + '"></span></label>';
        });

        html += '</div>';

        $(this).replaceWith(html);

        // Listen to the change event of checkboxes
        $(document).on('change', 'input[name="' + name + '"]', function() {
            var val = $('input[name="' + name + '"]:checked').val();
            $(target).attr('class', baseClass + val);
        });
    });

    /*
    |--------------------------------------------------------------------------
    | Icons
    |--------------------------------------------------------------------------
    |
    | Handle some behaviors in icons demo page
    |
    */

    $(document).on('change', '#icon-font-changer', function() {
        var size = $(this).find('option:selected').text();
        $('.demo-icons-list').attr('class', 'demo-icons-list icons-size-' + size);
    });

    $(document).on('mouseenter', '.demo-icons-list li', function() {
        var value = $(this).dataAttr('clipboard-text');
        $('#icon-selected').removeClass('text-secondary text-danger').addClass('text-info').text(value);
    });

    $(document).on('click', '.demo-icons-list li', function() {
        var value = $(this).dataAttr('clipboard-text');
        value += '<small class="sidetitle">COPIED</small>';
        $('#icon-selected').removeClass('text-secondary text-info').addClass('text-danger').html(value);
    });

    $(document).on('mouseleave', '.demo-icons-list', function() {
        $('#icon-selected').removeClass('text-info text-danger').addClass('text-secondary').text('Click an icon to copy the class name');
    });

    // Search
    $.expr.pseudos.iconsSearch = function(a, i, m) {
        return $(a).dataAttr('clipboard-text').toUpperCase().indexOf(m[3].toUpperCase()) >= 0;
    };

    $('#icons-search-input').on('keyup', function(e) {
        var s = $(this).val().trim(),
            icons = $(".tab-pane:not(#tab-search-result) .demo-icons-list li"),
            tabular = $('#icon-tabs').length;

        if (!tabular) {
            icons = $(".demo-icons-list li")
        }

        if (s === '') {
            icons.show();
            $('#icon-tabs li:eq(1) a').tab('show');
        } else {
            icons.not(':iconsSearch(' + s + ')').hide();
            icons.filter(':iconsSearch(' + s + ')').show();

            if (tabular) {
                // Show results in another tab
                $('#tab-search-result ul').html(icons.filter(':iconsSearch(' + s + ')').outerHTML());
                $('#icon-tabs li:first a').tab('show');
            }
        }
    });

    // Remove search results on change tab
    $('#icon-tabs li:first a').on('hide.bs.tab', function() {
        $('#icons-search-input').val('');
        $(".demo-icons-list li").show();
    });

    /*
     * Setting tab in the global quickview (#qv-global)
     */

    // Topbar background color
    $(document).on('change', 'input[name="global-topbar-color"]', function() {
        var val = $('input[name="global-topbar-color"]:checked').val();
        if (val == 'default') {
            $('body > .topbar').removeClass('topbar-inverse').css('background-color', '#fff');
        } else {
            $('body > .topbar').addClass('topbar-inverse').css('background-color', '#' + val);
        }
    });

    // Sidebar background color
    $(document).on('change', 'input[name="global-sidebar-color"]', function() {
        var val = $('input[name="global-sidebar-color"]:checked').val();
        $('.sidebar').removeClass('sidebar-light sidebar-dark sidebar-default');
        $('.sidebar').addClass('sidebar-' + val);
    });

    // Sidebar menu color
    $(document).on('change', 'input[name="global-sidebar-menu-color"]', function() {
        var val = $('input[name="global-sidebar-menu-color"]:checked').val();
        $(".sidebar").removeClass(function(index, className) {
            return (className.match(/(^|\s)sidebar-color-\S+/g) || []).join(' ');
        }).addClass('sidebar-color-' + val);

    });

    /*
    |--------------------------------------------------------------------------
    | Sidebar
    |--------------------------------------------------------------------------
    |
    | Handle some behaviors in sidebar demo page
    |
    */

    // Reset button
    $(document).on('click', '#sidebar-reset-btn', function() {
        $('.sidebar').attr('class', 'sidebar');
        $('.sidebar-header').removeClass('sidebar-header-inverse')
        $('.sidebar .menu').attr('class', 'menu');
        $('body').removeClass('sidebar-folded');
    });

    // Header background color
    $(document).on('change', 'input[name="sidebar-header-bg-color"]', function() {
        var val = $('input[name="sidebar-header-bg-color"]:checked').val();
        $('.sidebar-header').css('background-color', val);
    });

    /*
    |--------------------------------------------------------------------------
    | Timeline
    |--------------------------------------------------------------------------
    |
    | Handle some behaviors in timelines demo page
    |
    */

    // Content position
    $(document).on('click', '#timeline-alignment-selector .btn', function() {
        var val = $(this).children('input').val();
        $('#demo-timeline-alignment').attr('class', 'timeline timeline-content-' + val);
    });

    // Point size
    $(document).on('click', '#timeline-size-selector .btn', function() {
        var val = $(this).children('input').val();
        $('#demo-timeline-size').attr('class', 'timeline timeline-content-right timeline-point-' + val);
    });


    $("[data-seleciona_novo_cliente]").change(function(e) {
        $.post($(this).data("seleciona_novo_cliente") + "/" + $(this).val(), function(response) {
            //redirect
            if (response.redirect) {
                window.location.href = response.redirect;
            }
        }, "json");
    });

    //menssagens toast
    function toast(type, message) {
        $.toast({
            text: message, // Text that is to be shown in the toast
            icon: type, // Type of toast icon
            showHideTransition: 'slide', // fade, slide or plain
            allowToastClose: true, // Boolean value true or false
            hideAfter: 3000, // false to make it sticky or number representing the miliseconds as time after which toast needs to be hidden
            stack: 5, // false if there should be only one toast at a time or a number representing the maximum number of toasts to be shown at a time
            position: 'top-center', // bottom-left or bottom-right or bottom-center or top-left or top-right or top-center or mid-center or an object representing the left, right, top, bottom values
            textAlign: 'left', // Text alignment i.e. left, right or center
            loader: true, // Whether to show loader or not. True by default
            loaderBg: '#926dde', // Background color of the toast loader
        });
    }

});