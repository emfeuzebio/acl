@extends('adminlte::page')

@section('content_header')
    <div class="row mb-2">
        <div class="m-0 text-dark col-sm-6">
        <h1 class="m-0 text-dark"></h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="/home">Home</a></li>
                <li class="breadcrumb-item ">Administração</li>
                <li class="breadcrumb-item active">Usuários</li>
            </ol>
        </div>
    </div>
@stop

@section('content')

    <!-- datatables-users de Dados -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <!--área de título da Entidade-->
                        <div class="col-md-5 text-left h5"><b>Administração de Usuários</b></div>
                        <!--área de mensagens-->
                        <div class="col-md-4 text-left">
                            <div style="padding: 0px;  background-color: transparent;">
                                <div id="alert" class="alert alert-danger" style="margin-bottom: 0px; display: none; padding: 2px 5px 2px 5px;">
                                    <a class="close" onClick="$('.alert').hide()">&times;</a>  
                                    <div class="alert-content">Mensagem</div>
                                </div>
                            </div>                         
                        </div>
                        <!--área de botões-->
                        <div class="col-md-3 text-right">
                            <button id="btnRefresh" class="btn btn-default btn-sm btnRefresh" data-toggle="tooltip" title="Atualizar a tabela (Alt+R)">Refresh</button>
                            <button id="btnInserirNovo" class=" btn btn-success btn-sm" data-toggle="tooltip" title="Adicionar um novo registro (Alt+N)" >Inserir Novo</button>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <!-- compact | stripe | order-column | hover | cell-border | row-border | table-dark-->
                    <table id="datatables-users" class="table table-striped table-bordered table-hover table-sm compact" style="width:100%">
                        <thead></thead>
                        <tbody></tbody>
                        <tfoot></tfoot>                
                    </table>                 
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Editar Usuário -->
    <div class="modal fade" id="modalUserEditar" tabindex="-1" aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modalLabel">Modal title</h4>
                    <button type="button" class="close" data-bs-dismiss="modal" data-toggle="tooltip" title="Cancelar a operação (Esc ou Alt+C)" onClick="$('#modalUserEditar').modal('hide');">&times;</button>
                </div>
                <div class="modal-body">

                    <div class="row">

                        <div class="col-sm-12">

                            <form id="formUserEditar" name="formUserEditar" action="javascript:void(0)" class="form-horizontal" method="post">

                                <div class="form-group input-group-sm" id="form-group-id">
                                    <label class="form-label">ID</label>
                                    <input class="form-control" value="" type="text" id="id" name="id" placeholder="" readonly>
                                </div>

                                <div class="form-group input-group-sm">
                                    <label class="form-label">Nome</label>
                                    <input class="form-control" value="" type="text" id="name" name="name" placeholder="José da Silva e Silva" data-toggle="tooltip" title="Informe o Nome Completo do Usuário" >
                                    <div id="error-name" class="error invalid-feedback" style="display: none;"></div>
                                </div>

                                <div class="form-group input-group-sm">
                                    <label class="form-label">E-Mail</label>
                                    <input class="form-control" value="" type="text" id="email" name="email" placeholder="jose@dominio.com" data-toggle="tooltip" title="Informe o E-Mail do Usuário" >
                                    <div id="error-email" class="error invalid-feedback" style="display: none;"></div>
                                </div>

                                <div class="form-group input-group-sm">
                                    <label class="form-label">Senha</label>
                                    <input class="form-control" value="" type="password" id="password" name="password" placeholder="!@#Senha10" data-toggle="tooltip" title="Informe a Senha do Usuário" >
                                    <div id="error-password" class="error invalid-feedback" style="display: none;"></div>
                                </div>

                                <div class="form-group input-group-sm">
                                    <label class="form-label">Confirmação da Senha</label>
                                    <input class="form-control" value="" type="password" id="password_confirmation" name="password_confirmation" placeholder="!@#Senha10" data-toggle="tooltip" title="Informe a Senha do Usuário" >
                                    <div id="error-password_confirmation" class="error invalid-feedback" style="display: none;"></div>
                                </div>

                            </form>

                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <div class="col-md-5 text-left">
                        <label id="msgOperacaoEditar" class="error invalid-feedback" style="color: red; display: none; font-size: 12px;"></label> 
                    </div>
                    <div class="col-md-5 text-right">
                        <button type="button" class="btn btn-sm btn-secondary btnCancelar" data-bs-dismiss="modal" data-toggle="tooltip" title="Cancelar a operação (Esc ou Alt+C)" onClick="$('#modalUserEditar').modal('hide');">Cancelar</button>
                        <button type="button" class="btn btn-sm btn-primary" id="btnUserSalvar" data-toggle="tooltip" title="Salvar o registro (Alt+S)">Salvar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Conceder Perfil de Acesso a Usuário  -->
    <div class="modal fade" id="modalConcederPerfil" tabindex="-1" aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modalLabel">Modal title</h4>
                    <button type="button" class="close" data-bs-dismiss="modal" data-toggle="tooltip" title="Cancelar a operação (Esc ou Alt+C)" onClick="$('#modalConcederPerfil').modal('hide');">&times;</button>
                </div>
                <div class="modal-body">

                    <div class="row">
                        <div class="col-sm-12">
                            
                            <fieldset class="border p-2">
                                <legend class="w-auto h5">Perfis de Acesso</legend>

                                <!-- <input type="text" id="user_id" value=""> -->
                                <table id="tblPerfisConcedidos" class="table table-striped table-bordered table-hover table-sm compact" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Perfil de Acesso</th>
                                            <th>Concedidos ao Usuário</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                    </tbody>
                                    <tfoot></tfoot>                
                                </table> 
                            </fieldset>

                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <div class="col-md-5 text-left">
                        <label id="msgOperacaoPerfis" class="error invalid-feedback" style="color: red; display: none; font-size: 12px;"></label> 
                    </div>
                    <div class="col-md-5 text-right">
                        <button type="button" class="btn btn-secondary btnCancelar" data-bs-dismiss="modal" data-toggle="tooltip" title="Cancelar a operação (Esc ou Alt+C)" onClick="$('#modalConcederPerfil').modal('hide');">Fechar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- modal excluir registro -->
    <div class="modal fade" id="confirmaExcluirModal" tabindex="-1" aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Excluir Registro</h4>
                    <button type="button" class="close btnCancelar" data-bs-dismiss="modal" data-toggle="tooltip" title="Cancelar a operação (Esc ou Alt+C)" onClick="$('#msgOperacaoExcluir').text('');$('#confirmaExcluirModal').modal('hide');">&times;</button>
                </div>
                <div class="modal-body">
                    <p></p>
                    <label id="msgOperacaoExcluir" class="error invalid-feedback" style="color: red; display: none; font-size: 12px;"></label> 
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" data-toggle="tooltip" title="Cancelar a operação (Esc ou Alt+C)" onClick="$('#msgOperacaoExcluir').text('');$('#confirmaExcluirModal').modal('hide');">Cancelar</button>
                    <button type="button" class="btn btn-danger" data-toggle="tooltip" title="Confirmar a Exclusão" id="confirm">Excluir</button>
                </div>
            </div>
        </div>
    </div>    

    <!-- modal para exibir Alertas necessários -->
    <div class="modal fade" id="alertModal" tabindex="-1" aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog modal-sm modal-dialog-centered" role="document">            
            <div class="modal-content">
                <div class="modal-header alert-warning">
                    <h4 class="modal-title">Alerta</h4>
                    <button type="button" class="close btnCancelar" data-bs-dismiss="modal" data-toggle="tooltip" title="Cancelar a operação (Esc ou Alt+C)" onClick="$('#alertModal').modal('hide');">&times;</button>
                </div>
                <div class="modal-body"></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal" data-toggle="tooltip" title="Cancelar a operação (Esc ou Alt+C)" onClick="$('#alertModal').modal('hide');">Cancelar</button>
                </div>
            </div>
        </div>
    </div>    


    <script type="text/javascript">

        const ERROR_HTTP_STATUS = new Set([401, 419]); // 401-UNAUTHORIZED, 403-FORBIDDEN, 419-PAGE_EXPIRED, 404-NOT_FOUND, 500-INTERNAL_SERVER_ERROR

        $(document).ready(function () {

            let id = '';
            let perfil_id = '';

            $.ajaxSetup({
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                statusCode: { 401: function() { window.location.href = "/login"; } }
            });

            /*
            * Cria o datatables de Usuários
            */
            $('#datatables-users').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                autoWidth: true,
                // order: [ 1, 'asc' ],
                lengthMenu: [[5, 10, 15, 30, 50, -1], [5, 10, 15, 30, 50, "Todos"]], 
                pageLength: 10,
                ajax: "{{url("user")}}",
                // language: { url: "{{ asset('vendor/datatables/DataTables.pt_BR.json') }}" },
                columns: [
                    {"data": "id", "name": "users.id", "class": "dt-right", "title": "#", "width": "30px"},
                    {"data": "name", "name": "users.name", "class": "dt-left", "title": "Nome", "width": "150px",
                        render: function (data) { return '<b>' + data + '</b>';}},
                    {"data": "email", "name": "users.email", "class": "dt-left", "title": "E-Mail", "width": "auto"},
                    {"data": "perfis", "name": "users.perfis", "searchable":false, "orderable": false, "class": "dt-left", "title": "Perfis de Acesso", "width": "auto",
                        render: function(data, type, row) {
                            return '<button class="btn btn-xs btn-success btnConcederPerfil" data-toggle="tooltip" title="Conceder Perfis de Acesso ao Usuário">Conceder</button> ' + $.map(data, function(d, i) {
                                return '<button id="' + d.id + '" class="btn btn-xs btn-' + ( d.id <= 1 ? 'default' : 'info btnUserEditar' ) + '" data-toggle="tooltip" title="' + ( d.id <= 1 ? 'Entidade Padrão não pode ser editada.' : 'Administrar as Permissões concedidas à Entidade' ) + ' (' + d.nome + ')">' + d.nome + '</button> ';
                            }).join(' ');
                    }},
                    // {"data": "created_at", "name": "users.created_at", "class": "dt-center", "title": "Criado em", "width": "130px",
                    //     render: function (data) { return new Date(data).toLocaleString('pt-BR'); }
                    // },
                    {"data": "updated_at", "name": "users.updated_at", "title": "Atualizado em", "width": "130px", 
                        render: function (data) { return new Date(data).toLocaleString([], {day: "2-digit",month: "2-digit",year: "numeric",hour: "2-digit",minute: "2-digit"}); }
                    },
                    {"data": "id", "botoes": "", "orderable": false, "class": "dt-center", "title": "Ações", "width": "80px", 
                        render: function (data, type, row) { 
                            // As Estidades Básica (id=[1-5]) não podem ser excluídas
                            return ( row.id > 1 ? '<button class="btnUserEditar btn btn-primary btn-xs" data-toggle="tooltip" title="Editar o registro atual">Editar</button> ' : 
                                                  '<button class="btnUserEditar btn btn-default btn-xs" data-toggle="tooltip" title="Ver o registro atual">Ver</button>' )  + 
                                   ( row.id > 1 ? '<button class="btnUserExcluir btn btn-danger btn-xs" data-toggle="tooltip" title="Excluir o registro atual">Excluir</button> ' : ' ' ) +
                                   ''; 
                                //    '<button class="btnRotas btn btn-info btn-xs" data-toggle="tooltip" title="Editar as Rotas da Entidade atual">Perfis</button> '; 
                        }
                    },
                ]
            });

            /*
            * Editar o Usuário
            */
            $("#datatables-users tbody").delegate('tr td .btnUserEditar', 'click', function (e) {
                e.stopImmediatePropagation();            

                const id = $(this).parents('tr').attr("id");

                $.ajax({
                    type: "GET",
                    url: "{{url("user/show")}}",
                    data: {"id": id},
                    dataType: 'json',
                    success: function (data) {
                        $("#formUserEditar .invalid-feedback").text('').hide();
                        $('#formUserEditar').trigger("reset");
                        $('#modalUserEditar #form-group-id').show();            
                        $('#modalUserEditar #modalLabel').html((data.id == 1 ? 'Ver' : 'Editar') + ' Usuário');
                        $('#modalUserEditar').modal('show');
                        $('#msgOperacaoEditar').text('').hide();

                        // carrega os dados no form
                        $('#formRota #entidade_id').val(data.id);
                        $('#formUserEditar #id').val(data.id);
                        $('#formUserEditar #name').val(data.name);
                        $('#formUserEditar #email').val(data.email);
                        if(data.id == 1) {
                            $('#btnUserSalvar').hide();
                        } else {
                            $('#btnUserSalvar').show();
                        }
                    },
                    error: function (error) {
                        if (ERROR_HTTP_STATUS.has(error.status)) {
                            window.location.href = "{{ url('/login') }}";
                            return;
                        } 

                        $('#alertModal .modal-body').text(error.responseJSON.message)
                        $('#alertModal').modal('show');
                    }
                });                 
            });             

            /*
            * Excluir o Usuário
            */
            $("#datatables-users tbody").delegate('tr td .btnUserExcluir', 'click', function (e) {
                e.stopImmediatePropagation();            

                id = $(this).parents('tr').attr("id");

                //abre Form Modal Bootstrap e pede confirmação da Exclusão do Registro
                $('#msgOperacaoExcluir').text('');
                $("#confirmaExcluirModal .modal-body p").text('').text('Você está certo que deseja Excluir esta Entidade ID: ' + id + '?' + "\n\r\n\r" + 'Todas as Rotas que pertencem a esta Entidade também serão excluídas.');
                $('#confirmaExcluirModal').modal('show');

                //se confirmar a Exclusão, exclui o Registro via Ajax
                $('#confirmaExcluirModal').find('.modal-footer #confirm').on('click', function (e) {
                    e.stopImmediatePropagation();

                    $.ajax({
                        type: "POST",
                        url: "{{url("user/destroy")}}",
                        data: {"id": id},
                        dataType: 'json',
                        success: function (data) {
                            $("#alert .alert-content").text('Excluiu o registro ID ' + id + ' com sucesso.');
                            $('#alert').removeClass().addClass('alert alert-success').show().delay(5000).fadeOut(1000);
                            $('#confirmaExcluirModal').modal('hide');
                            $('#datatables-users').DataTable().ajax.reload(null, false);
                        },
                        error: function (error) {
                            if (ERROR_HTTP_STATUS.has(error.status)) {
                                window.location.href = "{{ url('/login') }}";
                                return;
                            } 

                            if(error.responseJSON.message.indexOf("1451") != -1) {
                                $('#msgOperacaoExcluir').text('Impossível EXCLUIR porque há registros relacionados. (SQL-1451)').show();
                            } else {
                                $('#msgOperacaoExcluir').text(error.responseJSON.message).show();
                            }
                        }
                    });
                    
                });

            });           

            /*
            * Salvar o Usuário
            */
            $('#btnUserSalvar').on("click", function (e) {
                e.stopImmediatePropagation();
                
                $(".invalid-feedback").text('').hide();    
                const formData = new FormData($('#formUserEditar').get(0));
                formData.append('ativo', getAtivoValue());

                $.ajax({
                    type: "POST",
                    url: "{{url("user/store")}}",
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function (data) {
                        $("#alert .alert-content").text('Salvou registro ID ' + data.id + ' com sucesso.');
                        $('#alert').removeClass().addClass('alert alert-success').show().delay(5000).fadeOut(1000);
                        $('#modalUserEditar').modal('hide');
                        $('#datatables-users').DataTable().ajax.reload(null, false);
                    },
                    error: function (error) {
                        if (ERROR_HTTP_STATUS.has(error.status)) {
                            window.location.href = "{{ url('/login') }}";
                            return;
                        } 

                        // validator: vamos exibir todas as mensagens de erro do validador, como o dataType não é JSON, precisa do responseJSON
                        $.each( error.responseJSON.errors, function( key, value ) {
                            $("#error-" + key ).text(value).show(); 
                        });

                        // exibe mensagem sobre sucesso da operação
                        if(error.responseJSON.message.indexOf("1062") != -1) {
                            $('#msgOperacaoEditar').text("Impossível SALVAR! Registro já existe. (SQL-1062)").show();
                        } 
                        if(! error.responseJSON.errors) {
                            $('#msgOperacaoEditar').text(error.responseJSON.message).show();
                        }
                    }
                });                
            });

            /*
            * Inserir Novo Usuário
            */
            $('#btnInserirNovo').on("click", function (e) {
                e.stopImmediatePropagation();

                // $.ajax({
                //     url: '/isAuthenticated',
                //     method: 'GET',
                //     success: function(response) {
                //         if (!response.authenticated) window.location.href = "{{ url('/') }}";
                //     },
                //     error: function(jqXHR) {
                //         if (jqXHR.status === 401) window.location.href = "{{ url('/') }}";
                //     }
                // });

                $('#modalUserEditar #form-group-id').hide();            // hide ID field
                $(".invalid-feedback").text('').hide();                 // hide all error displayed
                $('#formUserEditar').trigger("reset");
                $('#formUserEditar #ativo').prop('checked', true);      // default SIM
                $('#modalUserEditar #modalLabel').html('Novo Usuário');          
                $('#modalUserEditar').modal('show');                    // show modal 
                $('#btnUserSalvar').show();
            });

            /**
             * ações ao clicar no botão Conceder Perfil na linha da Tabela de Dados
             */
            $("#datatables-users tbody").delegate('tr td .btnConcederPerfil', 'click', function (e) {
                e.stopImmediatePropagation();                

                const id = $(this).parents('tr').attr("id");
                const user_nome = $(this).parents('tr').find('td:eq(1)').text();
                // alert('btnConcederPerfil ' + id);

                $.ajax({
                    type: "POST",
                    url: "{{url("user/listarPerfis")}}",
                    data: {"id": id},
                    dataType: 'json',
                    success: function (data) {

                        tblPerfis = '';
                        $habilitado = '';
                        // console.log(data);

                        $.each(data, function(i, obj){

                            tblPerfis += '' + 
                            '<tr id="' + obj.id + '">' + 
                                '<td>' + (i+1) + '</td>' + 
                                // '<td>' + obj.id + ' ' + obj.nome + '</td>' + 
                                '<td>' + obj.nome + '</td>' + 
                                '<td class="text-center">' + ( id > 1 ? // Usuário 1-Admin sempre têm todos Perfis de Acesso
                                    '<label class="switch">' + 
                                    '<input type="checkbox" id="chk' + obj.id + '" ' + obj.concedido + ' data-user_id="' + id + '" data-perfil_id="' + obj.id + '" class="switch-input" data-toggle="tooltip" title="Incluir">' + 
                                    '<span class="switch-label" data-on="SIM" data-off="NÃO"></span>' + 
                                    '<span class="switch-handle"></span>' + 
                                    '</label>' : 'SIM' ) +
                                '</td>' + 
                            '</tr>' + "\n";

                        })
                        tblPerfis = tblPerfis ? tblPerfis : '<tr><td class="text-center" colspan="2">Nenhuma registro</td></tr>';
                        $('#modalConcederPerfil #tblPerfisConcedidos tbody').empty().append(tblPerfis);       // adiciona as linhas na tabela do modal
                        $('#modalConcederPerfil #user_id').val(id);                                     // carrega o User ID no modal
                        // $('#modalConcederPerfil #modalLabel').html('<h5><span class="badge badge-primary">' + user_nome + '</span></h5>Lista de Perfis de Acesso');
                        $('#modalConcederPerfil #modalLabel').html('<h5><span class="badge badge-primary">' + user_nome + '</span></h5>');
                        $('#modalConcederPerfil').modal('show');
                    },
                    error: function (error) {
                        if (ERROR_HTTP_STATUS.has(error.status)) {
                            window.location.href = "{{ url('/login') }}";
                            return;
                        } 

                        $('#alertModal .modal-body').text(error.responseJSON.message)
                        $('#alertModal').modal('show');
                    }
                }); 
            });         
            
            /**
             * ações ao clicar sobre os checkbox do modal e mudar seu estado: tr td .btnInserirEntidade : tr td .chkEntidade
             */
            $("#tblPerfisConcedidos").delegate('tr :checkbox', 'click', function (e) {
                e.stopImmediatePropagation();                 

                var chkObjeto = $(this).attr("id");
                var chkUser = $(this).data("user_id");
                var chkPerfil = $(this).data("perfil_id");
                var chkCheked = $(this).is(":checked") ? "SIM" : "NÃO";
                var operacao = $(this).is(":checked") ? "inserir" : "excluir";
                // alert('Clicou SWITCH: ' + chkObjeto + ', User:' + chkUser + ', Perfil:' + chkPerfil + ', Cheked:' + chkCheked );

                $.ajax({
                    type: "POST",
                    url: "{{url("user/concederPerfil")}}",
                    data: {"operacao": operacao, "user_id":chkUser, "perfil_id":chkPerfil },
                    dataType: 'json',
                    success: function (data) {
                        //alert(data);
                        $('#btnRefresh').trigger('click');
                    },
                    error: function (error) {
                        if (ERROR_HTTP_STATUS.has(error.status)) {
                            window.location.href = "{{ url('/login') }}";
                            return;
                        } 

                        $('#' + chkObjeto + ':checkbox').prop('checked', (chkCheked == 'SIM' ? false : true));
                        // alert($('#' + chkObjeto).is(":checked"));
                        $('#alertModal .modal-body').text(error.responseJSON.message)
                        $('#alertModal').modal('show');                        
                    }
                });                 
               
            });       

            /*
            * Refresh da tabela de dados
            */
            $('#btnRefresh').on("click", function (e) {
                e.stopImmediatePropagation();
                // $.ajax({
                //     url: '/isAuthenticated',
                //     method: 'GET',
                //     success: function(response) {
                //         if (!response.authenticated) window.location.href = "{{ url('/') }}";
                //     },
                //     error: function(jqXHR) {
                //         if (jqXHR.status === 401) window.location.href = "{{ url('/') }}";
                //     }
                // });
                $('#datatables-users').DataTable().ajax.reload(null, false);
                $('#alert').trigger('reset').hide();
            });      

            // põe o foco no campo name 
            $('body').on('shown.bs.modal', '#modalUserEditar', function () {
                $('#name').focus();
            })

            function getAtivoValue() {
                return $('#ativo:checked').val() ? 'SIM': 'NÃO';
            }            

        });

    </script>    

@stop
