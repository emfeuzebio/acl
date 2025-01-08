@extends('adminlte::page')

@section('title', 'ACL Entidades e Rotas')

@section('content_header')
    <div class="row mb-2">
        <div class="m-0 text-dark col-sm-6">
        <h1 class="m-0 text-dark"></h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="/home">Home</a></li>
                <li class="breadcrumb-item ">Administração</li>
                <li class="breadcrumb-item active">Entidades e Rotas</li>
            </ol>
        </div>
    </div>

    <style>
        .dt-center {
            text-align: center !important;
        }       
    </style>    

@stop

@section('content')

    <!-- datatables-entidades de Dados -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <!--área de título da Entidade-->
                        <div class="col-md-5 text-left h5"><b>Administração de Entidades e Ações</b></div>
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
                            <button id="btnEntidadeNovo" class="btnInserirNovo btn btn-success btn-sm" data-toggle="tooltip" title="Adicionar um novo registro (Alt+N)" >Inserir Novo</button>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="table-responsive col-md-12">
                        <!-- compact | stripe | order-column | hover | cell-border | row-border | table-dark-->
                        <table id="datatables-entidades" class="table table-striped table-bordered table-hover table-sm compact" style="width:100%">
                            <thead></thead>
                            <tbody></tbody>
                            <tfoot></tfoot>                
                        </table>                 
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Editar Entidade e Permissões  -->
    <div class="modal fade" id="editarEntidadeModal" tabindex="-1" aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modalLabel">Modal title</h4>
                    <button type="button" class="close" data-bs-dismiss="modal" data-toggle="tooltip" title="Cancelar a operação (Esc ou Alt+C)" onClick="$('#editarEntidadeModal').modal('hide');">&times;</button>
                </div>
                <div class="modal-body">

                    <div class="row">

                        <div class="col-sm-12">

                            <form id="formEntidadeEditar" name="formEntidadeEditar" action="javascript:void(0)" class="form-horizontal" method="post">

                                <div class="form-group input-group-sm" id="form-group-id">
                                    <label class="form-label">ID</label>
                                    <input class="form-control" value="" type="text" id="id" name="id" placeholder="" readonly>
                                </div>

                                <div class="form-group input-group-sm">
                                    <label class="form-label">Model</label>
                                    <input class="form-control" value="" type="text" id="model" name="model" placeholder="" data-toggle="tooltip" title="" >
                                    <div id="error-model" class="error invalid-feedback" style="display: none;"></div>
                                </div>

                                <div class="form-group input-group-sm">
                                    <label class="form-label">Tabela</label>
                                    <input class="form-control" value="" type="text" id="tabela" name="tabela" placeholder="" data-toggle="tooltip" title="" >
                                    <div id="error-tabela" class="error invalid-feedback" style="display: none;"></div>
                                </div>

                                <div class="form-group input-group-sm">
                                    <label class="form-label">Descrição</label>
                                    <input class="form-control" value="" type="text" id="descricao" name="descricao" placeholder="" data-toggle="tooltip" title="" >
                                    <div id="error-descricao" class="error invalid-feedback" style="display: none;"></div>
                                </div>

                                <div class="form-group input-group-sm">
                                    <label class="form-label" data-toggle="tooltip" title="Marcar se a Entidade está Ativa">Ativo</label>
                                    <label class="switch">
                                        <input type="checkbox" id="ativo" name="ativo" class="switch-input" data-toggle="tooltip" title="Marcar se a Entidade está Ativa">
                                        <span class="switch-label" data-on="SIM" data-off="NÃO"></span>
                                        <span class="switch-handle"></span>
                                    </label>
                                    <div id="error-ativo" class="error invalid-feedback" style="display: none;"></div>
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
                        <button type="button" class="btn btn-sm btn-secondary btnCancelar" data-bs-dismiss="modal" data-toggle="tooltip" title="Cancelar a operação (Esc ou Alt+C)" onClick="$('#editarEntidadeModal').modal('hide');">Cancelar</button>
                        <button type="button" class="btn btn-sm btn-primary" id="btnEntidadeSalvar" data-toggle="tooltip" title="Salvar o registro (Alt+S)">Salvar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Editar Entidade e Permissões  -->
    <div class="modal fade" id="editarRotasModal" tabindex="-1" aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modalLabel">Modal title</h4>
                    <button type="button" class="close" data-bs-dismiss="modal" data-toggle="tooltip" title="Cancelar a operação (Esc ou Alt+C)" onClick="$('#editarRotasModal').modal('hide');">&times;</button>
                </div>
                <div class="modal-body">

                    <div class="col-sm-12">

                        <fieldset class="border p-2">
                            <legend class="w-auto h5">Ações (Rotas) Inseridas</legend>

                            <div class="table-responsive col-md-12">
                                <table id="tblRotas" class="table table-striped table-bordered table-hover table-sm compact" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Rota</th>
                                            <th>Ação</th>
                                            <th>Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <tbody id="tblRotasBody">
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                    </tbody>
                                    <tfoot></tfoot>                
                                </table> 
                            </div>
                        </fieldset>                        

                        <fieldset class="border p-2">
                            <legend class="w-auto h5">Inserir Nova Ação (Rota)</legend>
                            <form id="formRota" name="formRota" action="javascript:void(0)" class="form-horizontal" method="post">

                                <div class="form-group input-group-sm">
                                    <input class="form-control" value="" type="hidden" id="entidade_id" name="entidade_id">

                                    <label class="form-label">Rota</label>
                                    <input class="form-control" value="" type="text" id="rota" name="rota" placeholder="" data-toggle="tooltip" title="" >
                                    <div id="error-rota" class="error invalid-feedback" style="display: none;"></div>
                                </div>

                                <div class="form-group input-group-sm">
                                    <label class="form-label">Descrição</label>
                                    <input class="form-control input-sm" value="" type="text" id="descricao" name="descricao" placeholder="" data-toggle="tooltip" title="" >
                                    <div id="error-descricao" class="error invalid-feedback" style="display: none;"></div>
                                </div>
                                <button id="btnInserirRota" class="btn btn-success btn-sm" data-toggle="tooltip" title="Adicionar uma nova Rota à Entidade Atual">Inserir</button>

                            </form>

                        </fieldset>                        

                    </div>

                </div>
                <div class="modal-footer">
                    <div class="col-md-5 text-left">
                        <label id="msgOperacaoRota" class="error invalid-feedback" style="color: red; display: none; font-size: 12px;"></label> 
                    </div>
                    <div class="col-md-3 text-right">
                        <button type="button" class="btn btn-secondary btn-sm btnCancelar" data-bs-dismiss="modal" data-toggle="tooltip" title="Cancelar a operação (Esc ou Alt+C)" onClick="$('#editarRotasModal').modal('hide');">Fechar</button>
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
        let trId = 0;

        function ExcluirRota(id) {

                trId = id;

                // //abre Form Modal Bootstrap e pede confirmação da Exclusão do Registro
                $("#confirmaExcluirModal .modal-body p").text('Você está certo que deseja Excluir este registro ID: ' + trId + '?');
                $('#confirmaExcluirModal').modal('show');

                //se confirmar a Exclusão, exclui o Registro via Ajax
                $('#confirmaExcluirModal').find('.modal-footer #confirm').on('click', function (e) {
                    e.stopImmediatePropagation();

                    $.ajax({
                        type: "POST",
                        url: "{{url("rota/destroy")}}",
                        data: {"id": trId},
                        dataType: 'json',
                        success: function (data) {
                            $("#alert .alert-content").text('Excluiu o registro ID ' + id + ' com sucesso.');
                            $('#alert').removeClass().addClass('alert alert-success').show().delay(5000).fadeOut(1000);
                            $('#confirmaExcluirModal').modal('hide');
                            $('#tblRotas #tr' + trId).remove();
                        },
                        error: function (error) {
                            if (ERROR_HTTP_STATUS.has(error.status)) {
                                window.location.href = "{{ url('/login') }}";
                                return;
                            } 

                            // $('#msgOperacaoExcluir').text(error.responseJSON.message).show();
                            $('#alertModal .modal-body').text(error.responseJSON.message)
                            $('#alertModal').modal('show');
                        }
                    });
                });            
        }

        $(document).ready(function () {

            let id = '';
            let btnAcoes = '';

            $.ajaxSetup({
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                statusCode: { 401: function() { window.location.href = "/"; } }
            });

            /*
            * Cria a datatables da Entidade
            */
            $('#datatables-entidades').DataTable({
                // serverSide: true,
                processing: true,
                responsive: true,
                autoWidth: true,
                // order: [ 1, 'asc' ],
                lengthMenu: [[5, 10, 15, 30, 50, -1], [5, 10, 15, 30, 50, "Todos"]], 
                pageLength: 10,
                // ajax: "{{url("entidade")}}",
                ajax: {
                    type: "GET",
                    url: "{{url("entidade")}}",                             // rota
                    dataSrc: function (json) {
                        let autorizacoes = json.autorizacoes;           // Rotas autorizadas

                        // controle do botão Inserir Novo
                        if (json.autorizacoes.includes('entidade.store')) { $("#btnEntidadeNovo").show(); } else { $("#btnEntidadeNovo").hide(); }

                        // controle do botão Salvar do Modal de Edição
                        if (json.autorizacoes.includes('entidade.update')) { $("#btnEntidadeSalvar").show(); } else { $("#btnEntidadeSalvar").hide(); }

                        return json.data;                               // Retorna lista de dados para o DataTables
                    },                    
                },                
                rowId: 'id',                
                // language: { url: "{{ asset('vendor/datatables/DataTables.pt_BR.json') }}" },
                columns: [
                    {"data": "id", "name": "acl_entidades.id", "class": "dt-right", "title": "#", "width": "30px"},
                    {"data": "model", "name": "acl_entidades.model", "class": "dt-left", "title": "Entidade", "width": "150px",
                        render: function (data) { 
                            return '<b>' + data + '</b>';}},
                    {"data": "descricao", "name": "acl_entidades.descricao", "class": "dt-left", "title": "Descrição", "width": "300px"},
                    {"data": "ativo", "name": "acl_entidades.ativo", "class": "dt-center", "title": "Ativo", "width": "30px",  
                        render: function (data) { return '<span class="' + ( data == 'SIM' ? 'text-primary' : 'text-danger') + '">' + data + '</span>';}
                    },                    
                    {"data": "id", "botoes": "", "orderable": false, "class": "dt-center", "title": "Ações", "width": "80px", 
                        render: function(data, type, row) {

                            btnEditar = '';                 // esconde botoes
                            btnExcluir = '';                // esconde botoes
                            btnRotas = '';                // esconde botoes

                            // controle botão Ver
                            if (row.autorizacoes.includes('entidade.show')) {
                                btnEditar = '<button class="btnEntidadeEditar btn btn-primary btn-xs" data-toggle="tooltip" title="Ver o registro atual">Ver</button> ';
                            }

                            // controle botão Editar - As Estidades Básica (id=[1-9]) não podem ser alteradas
                            if (row.id > 9 && row.autorizacoes.includes('entidade.update')) {
                                btnEditar = '<button class="btnEntidadeEditar btn btn-primary btn-xs" data-toggle="tooltip" title="Editar o registro atual">Editar</button> ';                            
                            }

                            // controle botão Excluir - As Estidades Básica (id=[1-9]) não podem ser excluídas
                            if (row.id > 9 && row.autorizacoes.includes('entidade.destroy')) {
                                btnExcluir = '<button class="btnEntidadeExcluir btn btn-danger btn-xs" data-toggle="tooltip" title="Excluir o registro atual">Excluir</button> ';
                            }

                            if (row.autorizacoes.includes('entidade.rotas')) {
                                btnRotas = '<button class="btnRotas btn btn-info btn-xs" data-toggle="tooltip" title="Editar as Ações da Entidade atual">Ações</button> ';
                            }

                            return btnEditar + btnExcluir + btnRotas;
                        }
                    },
                ]
            });

            /*
            * Editar a Entidade
            */
            $("#datatables-entidades tbody").delegate('tr td .btnEntidadeEditar', 'click', function (e) {
                e.stopImmediatePropagation();            

                const id = $(this).parents('tr').attr("id");
                // alert(id);

                $.ajax({
                    type: "GET",
                    url: "{{url("entidade/show")}}",
                    data: {"id": id},
                    dataType: 'json',
                    success: function (data) {
                        $("#formEntidadeEditar .invalid-feedback").text('').hide();
                        $('#formEntidadeEditar').trigger("reset");
                        $('#editarEntidadeModal #modalLabel').html((data.id >= 1 && data.id <= 5 ? 'Ver' : 'Editar') + ' Entidade');
                        $('#msgOperacaoEditar').text('').hide();
                        $('#editarEntidadeModal').modal('show');

                        // implementar que seja automático foreach   
                        $('#formRota #entidade_id').val(data.id);
                        $('#formEntidadeEditar #id').val(data.id);
                        $('#formEntidadeEditar #model').val(data.model);
                        $('#formEntidadeEditar #tabela').val(data.tabela);
                        $('#formEntidadeEditar #descricao').val(data.descricao);
                        $('#formEntidadeEditar #ativo').prop('checked', (data.ativo == "SIM" ? true : false));
                        if(data.id >= 1 && data.id <= 5) {
                            $('#btnEntidadeSalvar').hide();
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
            * Excluir a Entidade
            */
            $("#datatables-entidades tbody").delegate('tr td .btnEntidadeExcluir', 'click', function (e) {
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
                        url: "{{url("entidade/destroy")}}",
                        data: {"id": id},
                        dataType: 'json',
                        success: function (data) {
                            $("#alert .alert-content").text('Excluiu o registro ID ' + id + ' com sucesso.');
                            $('#alert').removeClass().addClass('alert alert-success').show().delay(5000).fadeOut(1000);
                            $('#confirmaExcluirModal').modal('hide');
                            $('#datatables-entidades').DataTable().ajax.reload(null, false);
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
            * Salvar a Entidade
            */
            $('#btnEntidadeSalvar').on("click", function (e) {
                e.stopImmediatePropagation();
                
                $(".invalid-feedback").text('').hide();    
                const formData = new FormData($('#formEntidadeEditar').get(0));
                formData.append('ativo', getAtivoValue());

                $.ajax({
                    type: "POST",
                    url: "{{url("entidade/store")}}",
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function (data) {
                        $("#alert .alert-content").text('Salvou registro ID ' + data.id + ' com sucesso.');
                        $('#alert').removeClass().addClass('alert alert-success').show().delay(5000).fadeOut(1000);
                        $('#editarEntidadeModal').modal('hide');
                        $('#datatables-entidades').DataTable().ajax.reload(null, false);
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
                        } else {
                            $('#msgOperacaoEditar').text(error.responseJSON.message).show();
                        }
                    }
                });                
            });


            /*
            * Inserir Nova Entidade
            */
            $('#btnEntidadeNovo').on("click", function (e) {
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

                $('#editarEntidadeModal #form-group-id').hide();            // hide ID field
                $('#formEntidadeEditar').trigger("reset");
                $('#editarEntidadeModal #modalLabel').html('Nova Entidade');          
                $(".invalid-feedback").text('').hide();                     // hide all error displayed
                $('#formEntidadeEditar #ativo').prop('checked', true);      // default SIM
                $('#editarEntidadeModal').modal('show');                    // show modal 
                $('#btnEntidadeSalvar').show();
                // $('#editarEntidadeModal #model').focus();
            });

            /*
            * Insere um nova Rota na Entidade
            */
            $('#btnInserirRota').on("click", function (e) {
                e.stopImmediatePropagation();
                $("#formRota .invalid-feedback").text('').hide();

                formData = new FormData($('#formRota').get(0));

                $.ajax({
                    type: "POST",
                    url: "{{url("rota/store")}}",
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function (data) {
                        // $("#alert .alert-content").text('Salvou registro ID ' + data.id + ' com sucesso.');
                        // $('#alert').removeClass().addClass('alert alert-success').show().delay(5000).fadeOut(1000);
                        // $("#msgOperacaoRota").text('Salvou registro ID ' + data.id + ' com sucesso.');
                        $('#msgOperacaoRota').removeClass().addClass('alert alert-success')
                            .text('Salvou registro ID ' + data.id + ' com sucesso.').show().delay(5000).fadeOut(1000);

                        $('#formRota').trigger("reset");

                        // atualiza tabela de Rotas Inseridas
                        tblRotas += '<tr id="tr' + data.id + '"><td></td><td>' + formData.get('rota') + '</td><td>' + formData.get('descricao') + '</td><td><button id="' + data.id + '" onClick="ExcluirRota(' + data.id + ')" class="btnExcluirRota btn btn-danger btn-xs" data-toggle="tooltip" title="Excluir Rota">Excluir</button></td></tr>';
                        $('#tblRotasBody').empty().append(tblRotas);  //adiciona as linhas na tabela      
                    },
                    error: function (error) {
                        if (ERROR_HTTP_STATUS.has(error.status)) {
                            window.location.href = "{{ url('/login') }}";
                            return;
                        } 

                        $('#alertModal .modal-body').text(error.responseJSON.message)
                        $('#alertModal').modal('show');

                        // validator: vamos exibir todas as mensagens de erro do validador
                        // como o dataType não é JSON, precisa do responseJSON
                        $("#formRota .invalid-feedback").text('').hide();
                        $.each( error.responseJSON.errors, function( key, value ) {
                            $("#formRota #error-" + key ).text(value).show(); 
                        });
                        // exibe mensagem sobre sucesso da operação
                        if(error.responseJSON.message.indexOf("1062") != -1) {
                            $('#msgOperacaoEditar').text("Impossível SALVAR! Registro já existe. (SQL-1062)").show();
                        } else if(error.responseJSON.exception) {
                            $('#msgOperacaoEditar').text(error.responseJSON.message).show();
                        }
                    }
                });                
            });

            /*
            * Editar as Rotas da Entidade
            */
            $("#datatables-entidades tbody").delegate('tr td .btnRotas', 'click', function (e) {
                e.stopImmediatePropagation();            

                const id = $(this).parents('tr').attr("id");
                const rota_nome = $(this).parents('tr').find('td:eq(1)').text();
                // alert(rota_nome);

                $.ajax({
                    type: "GET",
                    url: "{{url("entidade/rotas")}}",
                    data: {"id": id},
                    dataType: 'json',
                    success: function (data) {
                        $('#editarRotasModal #modalLabel').html('<h5><span class="badge badge-primary">' + rota_nome + '</span></h5>Editar Ações (Rotas) da Entidade');
                        $('#editarRotasModal #entidade_id').val(id);
                        $('#editarRotasModal #formRota').trigger('reset');      

                        $(".invalid-feedback").text('').hide();
                        $('#editarRotasModal').modal('show');

                        //tblRotasBody
                        // atualiza tabela de Rotas Inseridas
                        tblRotas = '';
                        $.each(data, function(i, obj){
                            tblRotas += '<tr id="tr' + obj.id + '"><td>' + (i+1) + '</td><td>' + obj.rota + '</td><td>' + obj.descricao + '</td><td><button id="' + obj.id + '" onClick="ExcluirRota(' + obj.id + ')" class="btnExcluirRota btn btn-danger btn-xs" data-toggle="tooltip" title="Excluir esta Ação (Rota)">Excluir</button></td></tr>';
                        })
                        tblRotas = tblRotas ? tblRotas : '<tr><td class="text-center" colspan="3">Nenhuma Rota Inserida</td></tr>';
                        $('#tblRotasBody').empty().append(tblRotas);  //adiciona as linhas na tabela                        

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
                $('#datatables-entidades').DataTable().ajax.reload(null, false);
                $('#alert').trigger('reset').hide();
            });      

            // put the focus on de name field
            $('body').on('shown.bs.modal', '#editarEntidadeModal', function () {
                $('#model').focus();
            })

            function getAtivoValue() {
                return $('#ativo:checked').val() ? 'SIM': 'NÃO';
            }            

        });

    </script>    

@stop
