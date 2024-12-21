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
                <li class="breadcrumb-item active">Perfís de Acesso</li>
            </ol>
        </div>
    </div>
@stop

@section('content')

    <!-- datatables-perfils de Dados -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <!--área de título da Entidade-->
                        <div class="col-md-4 text-left h5"><b>Administração de Perfis de Acesso</b></div>
                        <!--área de mensagens-->
                        <div class="col-md-5 text-left">
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
                            <button id="btnPerfilNovo" class="btnInserirNovo btn btn-success btn-sm" data-toggle="tooltip" title="Adicionar um novo registro (Alt+N)" >Inserir Novo</button>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <!-- compact | stripe | order-column | hover | cell-border | row-border | table-dark-->
                    <table id="datatables-perfils" class="table table-striped table-bordered table-hover table-sm compact" style="width:100%">
                        <thead></thead>
                        <tbody></tbody>
                        <tfoot></tfoot>                
                    </table>                 
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Editar Perfil  -->
    <div class="modal fade" id="modalPerfilEditar" tabindex="-1" aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modalLabel">Modal title</h4>
                    <button type="button" class="close" data-bs-dismiss="modal" data-toggle="tooltip" title="Cancelar a operação (Esc ou Alt+C)" onClick="$('#modalPerfilEditar').modal('hide');">&times;</button>
                </div>
                <div class="modal-body">

                    <div class="row">

                        <div class="col-sm-12">

                            <form id="formPerfilEditar" name="formPerfilEditar" action="javascript:void(0)" class="form-horizontal" method="post">

                                <div class="form-group input-group-sm" id="form-group-id">
                                    <label class="form-label">ID</label>
                                    <input class="form-control" value="" type="text" id="id" name="id" placeholder="" readonly data-toggle="tooltip" title="ID do Perfil de Acesso">
                                </div>

                                <div class="form-group input-group-sm">
                                    <label class="form-label">Nome</label>
                                    <input class="form-control" value="" type="text" id="nome" name="nome" placeholder="" data-toggle="tooltip" title="Informe o Nome do Perfil de Acesso" >
                                    <div id="error-nome" class="error invalid-feedback" style="display: none;"></div>
                                </div>

                                <div class="form-group input-group-sm">
                                    <label class="form-label">Descrição</label>
                                    <textarea class="form-control" id="descricao" name="descricao" placeholder="" data-toggle="tooltip" title="Informe a Descrição do Perfil de Acesso" rows="4"></textarea>
                                    <div id="error-descricao" class="error invalid-feedback" style="display: none;"></div>
                                </div>

                                <div class="form-group input-group-sm">
                                    <label class="form-label" data-toggle="tooltip" title="Marcar se o Perfil de Acesso está Ativo">Ativo</label>
                                    <label class="switch">
                                        <input type="checkbox" id="ativo" name="ativo" class="switch-input" data-toggle="tooltip" title="Marcar se  o Perfil de Acesso está Ativo">
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
                    <div class="col-sm-6 text-left">
                        <label id="msgOperacaoEditar" class="error invalid-feedback" style="color: red; display: none; font-size: 12px;"></label> 
                    </div>
                    <div class="col-sm-4 text-right">
                        <button type="button" class="btn btn-sm btn-secondary btnCancelar" data-bs-dismiss="modal" data-toggle="tooltip" title="Cancelar a operação (Esc ou Alt+C)" onClick="$('#modalPerfilEditar').modal('hide');">Cancelar</button>
                        <button type="button" class="btn btn-sm btn-primary" id="btnPerfilSalvar" data-toggle="tooltip" title="Salvar o registro (Alt+S)">Salvar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>    

    <!-- Modal Editar Entidade e Permissões  -->
    <div class="modal fade" id="modalAutorizacoesEditar" tabindex="-1" aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modalLabel">Modal title</h4>
                    <button type="button" class="close" data-bs-dismiss="modal" data-toggle="tooltip" title="Cancelar a operação (Esc ou Alt+C)" onClick="$('#modalAutorizacoesEditar').modal('hide');">&times;</button>
                </div>
                <div class="modal-body">

                    <div class="row">

                        <div class="col-sm-12">
                            
                            <fieldset class="border p-2">
                                <legend class="w-auto h5">Ações (Rotas) da Entidade</legend>
                                <table id="tblPermissoes" class="table table-striped table-bordered table-hover table-sm compact" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Ação</th>
                                            <th>Rota</th>
                                            <th>Autorizado</th>
                                            {{-- <th>Ação</th> --}}
                                        </tr>
                                    </thead>
                                    <tbody id="tblPermissoesLinhas">
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            {{-- <td></td> --}}
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
                        <button type="button" class="btn btn-secondary btnCancelar" data-bs-dismiss="modal" data-toggle="tooltip" title="Cancelar a operação (Esc ou Alt+C)" onClick="$('#modalAutorizacoesEditar').modal('hide');">Fechar</button>
                        <!-- <button type="button" class="btn btn-primary" id="btnSalvar" data-toggle="tooltip" title="Salvar o registro (Alt+S)">Salvar</button> -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Editar Entidade e Permissões  -->
    <div class="modal fade" id="inserirEntidadeModal" tabindex="-1" aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modalLabel">Modal title</h4>
                    <button type="button" class="close" data-bs-dismiss="modal" data-toggle="tooltip" title="Cancelar a operação (Esc ou Alt+C)" onClick="$('#inserirEntidadeModal').modal('hide');">&times;</button>
                </div>
                <div class="modal-body">

                    <div class="row">
                        <div class="col-sm-12">
                            
                            <fieldset class="border p-2">
                                <legend class="w-auto h5">Entidades com acesso concedido</legend>

                                <input type="hidden" id="perfil_id" value="">
                                <table id="tblEntidadesNaoInseridas" class="table table-striped table-bordered table-hover table-sm compact" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Entidade</th>
                                            <th>Concedida ao Perfil</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <tbody id="tblEntidadesBody">
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
                        <button type="button" class="btn btn-secondary btnCancelar" data-bs-dismiss="modal" data-toggle="tooltip" title="Cancelar a operação (Esc ou Alt+C)" onClick="$('#inserirEntidadeModal').modal('hide');">Fechar</button>
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

        $(document).ready(function () {

            let entidadePadrao = 0;
            let id = '';
            let btnAcoes = '';

            $.ajaxSetup({
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                statusCode: { 401: function() { window.location.href = "/login"; } }
            });

            // Admin pode tudo
            btnAcoes  = '<button class="btnPerfilEditar btn btn-primary btn-xs" data-toggle="tooltip" title="Editar o registro atual">Editar</button> ';
            btnAcoes += '<button class="btnExcluir btn btn-danger btn-xs" data-toggle="tooltip" title="Excluir o registro atual">Excluir</button> ';
            // btnAcoes += '<button class="btnPerfis btn btn-info btn-xs" data-toggle="tooltip" title="Editar o Perfis de Acesso do Usuário atual">Perfis</button> '; 

            /*
            * Cria a datatables da Entidade
            */
            $('#datatables-perfils').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                autoWidth: true,
                // order: [ 1, 'asc' ],
                lengthMenu: [[5, 10, 15, 30, 50, -1], [5, 10, 15, 30, 50, "Todos"]], 
                pageLength: 10,
                ajax: "{{url("perfil")}}",
                // language: { url: "{{ asset('vendor/datatables/DataTables.pt_BR.json') }}" },
                columns: [
                    {"data": "id", "name": "acl_perfils.id", "class": "dt-right", "title": "#", "width": "30px"},
                    {"data": "nome", "name": "acl_perfils.nome", "class": "dt-left", "title": "Perfil de Acesso", "width": "150px",
                        render: function (data) { 
                            return '<b>' + data + '</b>';}},
                    {"data": "descricao", "name": "acl_perfils.descricao", "class": "dt-left", "title": "Descrição", "width": "300px"},
                    {"data": "entidades", "entidades": "", "orderable": false, "class": "dt-left", "title": "Entidades em que tês Autorizações", "width": "auto",
                        render: function(data, type, row) {
                            return '<button class="btn btn-xs btn-success btnNovoEntidade" data-toggle="tooltip" title="Administrar Entidades concedidas a este Perfil">Administrar</button> ' + $.map(data, function(d, i) {
                                return '<button id="' + d.id + '" class="btn btn-xs btn-' + ( d.id <= entidadePadrao ? 'default' : 'info btnEntidadeEditar' ) + '" data-toggle="tooltip" title="' + ( d.id <= entidadePadrao ? 'Entidade Padrão não pode ser editada.' : 'Administrar as Permissões concedidas à Entidade' ) + ' (' + d.model + ')">' + d.model + '</button> ';
                            }).join(' ');
                    }},
                    {"data": "ativo", "name": "acl_perfils.ativo", "class": "dt-center", "title": "Ativo",  
                        render: function (data) { return '<span class="' + ( data == 'SIM' ? 'text-primary' : 'text-danger') + '">' + data + '</span>';}
                    },                    
                    {"data": "id", "botoes": "", "orderable": false, "class": "dt-center", "title": "Ações", "width": "80px", 
                        render: function (data, type) { return btnAcoes; }
                    },
                ]
            });

            /*
            * Inserir Novo Perfil de Acesso
            */
            $('#btnPerfilNovo').on("click", function (e) {
                e.stopImmediatePropagation();

                $('#modalPerfilEditar #form-group-id').hide();
                $('#formPerfilEditar').trigger("reset");
                $('#modalPerfilEditar #modalLabel').html('Novo Perfil de Acesso');          
                $(".invalid-feedback").text('').hide();                     // hide all error displayed
                $('#formPerfilEditar #ativo').prop('checked', true);      // default SIM
                $('#modalPerfilEditar').modal('show');                    // show modal 
                $('#btnPerfilSalvar').show();
                // $('#modalPerfilEditar #model').focus();
            });            

            /*
            * Editar as Permissões da Entidade
            */
            $("#datatables-perfils tbody").delegate('tr td .btnEntidadeEditar', 'click', function (e) {
                e.stopImmediatePropagation();            

                const id = $(this).attr('id');
                const perfil_id = $(this).parents('tr').attr("id");
                const perfil_nome = $(this).parents('tr').find('td:eq(1)').text();
                // alert($(this).parents('tr').find('td:eq(1)').text());

                $.ajax({
                    type: "GET",
                    url: "{{url("entidade/show")}}",
                    data: {"perfil_id":perfil_id, "id": id},
                    dataType: 'json',
                    success: function (data) {
                        $("#formPerfilEditar .invalid-feedback").text('').hide();
                        $('#formPermissao').trigger("reset");
                        $('#modalAutorizacoesEditar #modalLabel').html('<h5><span class="badge badge-primary">' + perfil_nome + '</span></h5>Autorizar Ações (Rotas) da Entidade');
                        $('#modalAutorizacoesEditar').modal('show');

                        // implementar que seja automático foreach   
                        $('#formPermissao #perfil_id').val(perfil_id);
                        $('#formPermissao #entidade_id').val(data.id);
                        $('#formPerfilEditar #id').val(data.id);
                        $('#formPerfilEditar #model').val(data.model);
                        $('#formPerfilEditar #tabela').val(data.tabela);
                        $('#formPerfilEditar #descricao').val(data.descricao);
                        $('#formPerfilEditar #ativo').val(data.ativo);

                        // monta tabela de autorizações
                        tblPermissoesLinhas = '';
                        $.each(data.autorizacoes, function(i, obj){
                            tblPermissoesLinhas += '' + 
                            '<tr id="tr' + obj.id + '">' + 
                                '<td>' + (i+1) + '</td>' + 
                                '<td>' + obj.descricao + '</td>' + 
                                '<td>' + obj.rota + '</td>' + 
                                '<td class="text-center">' + 
                                    '<label class="switch">' + 
                                    '<input type="checkbox" id="' + obj.id + '" ' + ( obj.ativo == 'SIM' ? 'checked' : '' ) + ' data-perfil_id="' + id + '" data-entidade_id="' + obj.id + '" class="switch-input" data-toggle="tooltip" title="Incluir">' + 
                                    '<span class="switch-label" data-on="SIM" data-off="NÃO"></span>' + 
                                    '<span class="switch-handle"></span>' + 
                                    '</label>' + 
                                '</td>' + 
                            '</tr>';
                        })
                        tblPermissoesLinhas = tblPermissoesLinhas ? tblPermissoesLinhas : '<tr><td class="text-center" colspan="4">Nenhuma Permissão concedida</td></tr>';
                        $('#tblPermissoesLinhas').empty().append(tblPermissoesLinhas);  //adiciona as linhas na tabela
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
            * Insere nova Permissão na Entidade
            */
            $('#btnInserirPermissao').on("click", function (e) {
                e.stopImmediatePropagation();
                $("#formPermissao .invalid-feedback").text('').hide();

                formData = new FormData($('#formPermissao').get(0));

                $.ajax({
                    type: "POST",
                    url: "{{url("autorizacao/store")}}",  
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function (data) {
                        $("#alert .alert-content").text('Salvou registro ID ' + data.id + ' com sucesso.');
                        $('#alert').removeClass().addClass('alert alert-success').show().delay(5000).fadeOut(1000);
                        $('#formPermissao').trigger("reset");
                        $('#formPermissao #entidade_id').val(formData.get('entidade_id'));
                        tblPermissoesLinhas = '<tr id="tr' + data.id + '"><td>' + formData.get('descricao') + '</td><td>' + formData.get('rota') + '</td><td>' + formData.get('ativo') + '</td><td><button id="' + data.id + '" onClick="ExcluirPermissao(' + data.id + ')" class="btnExcluirPermissao btn btn-danger btn-xs" data-toggle="tooltip" title="Excluir o registro atual">Excluir</button></td></tr>';
                        $('#tblPermissoesLinhas').append(tblPermissoesLinhas);  //adiciona as linhas na tabela
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
                        $("#formPermissao .invalid-feedback").text('').hide();
                        $.each( error.responseJSON.errors, function( key, value ) {
                            $("#formPermissao #error-" + key ).text(value).show(); 
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
            * Perfis de Acesso
            */
            $("#datatables-perfils tbody").delegate('tr td .btnPerfis', 'click', function (e) {
                e.stopImmediatePropagation();            

                const id = $(this).parents('tr').attr("id");

                $.ajax({
                    type: "POST",
                    url: "{{url("users/perfis")}}",
                    data: {"id": id},
                    dataType: 'json',
                    success: function (data) {
                        $('#modalLabel').html('Editar Perfis e Permissões');
                        $(".invalid-feedback").text('').hide();
                        $('#modalAutorizacoesEditar').modal('show');

                        // implementar que seja automático foreach   
                        // $('#id').val(data.id);
                        // $('#sigla').val(data.sigla);
                        // $('#descricao').val(data.descricao);
                        // $('#ativo').bootstrapToggle(data.ativo == "SIM" ? 'on' : 'off');
                    },
                    error: function (error) {
                        if (ERROR_HTTP_STATUS.has(error.status)) {
                            window.location.href = "{{ url('/login') }}";
                            return;
                        } 

                        $('#alertModal .modal-body').text(error.responseJSON.message)
                        $('#alertModal').modal('show');

                        // if (error.responseJSON.message) {
                        //     $("#alert .alert-content").text('ERRO: ' + error.responseJSON.message + 'A operação foi cancelada.');
                        //     $('#alert').removeClass().addClass('alert alert-danger').show().delay(4000).fadeOut(1000);
                        //     // window.location.href = "{{ url('/') }}";
                        // }
                    }
                }); 
            });          

            // $('.btnNovoEntidade').on("click", function (e) {
            //     e.stopImmediatePropagation();
            $("#datatables-perfils tbody").delegate('tr td .btnNovoEntidade', 'click', function (e) {
                e.stopImmediatePropagation();                

                const id = $(this).parents('tr').attr("id");
                const perfil_nome = $(this).parents('tr').find('td:eq(1)').text();
                // alert('btnNovoEntidade ' + id);

                $.ajax({
                    type: "GET",
                    url: "{{url("entidade/list")}}",
                    data: {"id": id},
                    dataType: 'json',
                    success: function (data) {

                        tblEntidades = '';
                        $habilitado = '';
                        // console.log(data);

                        $.each(data, function(i, obj){

                            tblEntidades += '' + 
                            '<tr id="tr' + obj.id + '">' + 
                                '<td>' + (i+1) + '</td>' + 
                                '<td>' + obj.model + '</td>' + 
                                '<td class="text-center">' + ( obj.id > 6 ?  
                                    '<label class="switch">' + 
                                    '<input type="checkbox" id="' + obj.id + '" ' + obj.concedido + ' data-perfil_id="' + id + '" data-entidade_id="' + obj.id + '" class="switch-input" data-toggle="tooltip" title="Incluir">' + 
                                    '<span class="switch-label" data-on="SIM" data-off="NÃO"></span>' + 
                                    '<span class="switch-handle"></span>' + 
                                    '</label>' : 'SIM' ) +
                                '</td>' + 
                            '</tr>' + "\n";

                        })
                        tblEntidades = tblEntidades ? tblEntidades : '<tr><td class="text-center" colspan="2">Nenhuma registro</td></tr>';
                        $('#tblEntidadesBody').empty().append(tblEntidades);  //adiciona as linhas na tabela                        

                        // abre modal
                        $('#inserirEntidadeModal #perfil_id').val(id);

                        $('#inserirEntidadeModal #modalLabel').html('<h5><span class="badge badge-primary">' + perfil_nome + '</span></h5>Lista de Entidades');
                        $('#inserirEntidadeModal').modal('show');
                        
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
             * ações ao clicar sobre os checkbox e mudar seu estado: tr td .btnInserirEntidade : tr td .chkEntidade
             */
            $("#tblEntidadesNaoInseridas tbody").delegate('tr :checkbox', 'click', function (e) {
                e.stopImmediatePropagation();                 
                
                var perfil_id = $('#inserirEntidadeModal #perfil_id').val();
                //alert(perfil_id);

                var chkObjeto = $(this).attr("id");
                var chkEntidade = $(this).data("entidade_id");
                var chkCheked = $(this).is(":checked") ? "S" : "N";
                var operacao = $(this).is(":checked") ? "inserir" : "excluir";
                var chkPerfil = operacao == 'inserir' ? perfil_id : $(this).data("perfil_id");                
                //alert('Clicou SWITCH: ' + chkObjeto + ', Perfil:' + chkPerfil + ', Entidade:' + chkEntidade + ',  Cheked:' + chkCheked );

                $.ajax({
                    type: "POST",
                    url: "{{url("perfil/concederEntidade")}}",
                    data: {"operacao": operacao, "id":chkObjeto, "perfil_id":chkPerfil, "entidade_id":chkEntidade },
                    dataType: 'json',
                    success: function (data) {
                        //alert(data);
                        // $('#inserirEntidadeModal').modal('hide');
                        $('#btnRefresh').trigger('click');
                    },
                    error: function (error) {
                        if (ERROR_HTTP_STATUS.has(error.status)) {
                            window.location.href = "{{ url('/login') }}";
                            return;
                        } 

                        $('#'+chkObjeto).prop('checked', (chkCheked == 'SIM' ? false : true));
                        $('#alertModal .modal-body').text(error.responseJSON.message)
                        $('#alertModal').modal('show');                        
                    }
                });                 
            });       
            
            
            /**
             * ações ao clicar sobre os checkbox e mudar seu estado: tr td .btnInserirEntidade : tr td .chkEntidade
             */
             $("#tblPermissoes tbody").delegate('tr :checkbox', 'click', function (e) {
                e.stopImmediatePropagation();                 

                var chkObjeto = $(this).attr("id");
                var chkCheked = $(this).is(":checked") ? "SIM" : "NÃO";
                //alert('Clicou SWITCH: ' + chkObjeto + ', Cheked:' + chkCheked );

                $.ajax({
                    type: "POST",
                    url: "{{url("autorizacao/authorizar")}}",
                    data: { "id":chkObjeto, "ativo":chkCheked },
                    dataType: 'json',
                    success: function (data) {
                        //$('#inserirEntidadeModal').modal('hide');
                        //EUZ - controlar o sucesso. Caso erro o Switch deve voltar ao estado anterior ao click
                        //alert(data.ativo);
                    },
                    error: function (error) {
                        if (ERROR_HTTP_STATUS.has(error.status)) {
                            window.location.href = "{{ url('/login') }}";
                            return;
                        } 

                        // alert(chkCheked); - retorna o checkbox para o estado anterior
                        $('#'+chkObjeto).prop('checked', (chkCheked == 'SIM' ? false : true));
                        $('#alertModal .modal-body').text(error.responseJSON.message)
                        $('#alertModal').modal('show');                        
                    }
                });                 
               
            });                     


            /*
            * Delete button action
            */
            $("#datatables-perfils tbody").delegate('tr td .btnExcluir', 'click', function (e) {
                e.stopImmediatePropagation();            

                id = $(this).parents('tr').attr("id");

                //abre Form Modal Bootstrap e pede confirmação da Exclusão do Registro
                $('#msgOperacaoExcluir').text('');
                $("#confirmaExcluirModal .modal-body p").text('').text('Você está certo que deseja Excluir este registro ID: ' + id + '?');
                $('#confirmaExcluirModal').modal('show');

                //se confirmar a Exclusão, exclui o Registro via Ajax
                $('#confirmaExcluirModal').find('.modal-footer #confirm').on('click', function (e) {
                    e.stopImmediatePropagation();

                    $.ajax({
                        type: "POST",
                        url: "{{url("perfil/destroy")}}",
                        data: {"id": id},
                        dataType: 'json',
                        success: function (data) {
                            $("#alert .alert-content").text('Excluiu o registro ID ' + id + ' com sucesso.');
                            $('#alert').removeClass().addClass('alert alert-success').show().delay(5000).fadeOut(1000);
                            $('#confirmaExcluirModal').modal('hide');
                            $('#datatables-perfils').DataTable().ajax.reload(null, false);
                        },
                        error: function (error) {
                            if (ERROR_HTTP_STATUS.has(error.status)) {
                                window.location.href = "{{ url('/login') }}";
                                return;
                            } 

                            $('#msgOperacaoExcluir').text(error.responseJSON.message).show();
                            if(error.responseJSON.message.indexOf("1451") != -1) {
                                $('#msgOperacaoExcluir').text('Impossível EXCLUIR porque há registros relacionados. (SQL-1451)').show();
                            } else {
                                $('#msgOperacaoExcluir').text(error.responseJSON.message).show();

                                $('#alertModal .modal-body').text(error.responseJSON.message)
                                $('#alertModal').modal('show');
                            }
                        }
                    });
                });
            });           

            /*
            * Edit button action
            */
            $("#datatables-perfils tbody").delegate('tr td .btnPerfilEditar', 'click', function (e) {
                e.stopImmediatePropagation();            

                const id = $(this).parents('tr').attr("id");

                $.ajax({
                    type: "GET",
                    url: "{{url("perfil/show")}}",
                    data: {"id": id},
                    dataType: 'json',
                    success: function (data) {
                        $("#formPerfilEditar .invalid-feedback").text('').hide();
                        $('#formPerfilEditar').trigger("reset");
                        $('#modalPerfilEditar #modalLabel').html((data.id >= 1 && data.id <= 5 ? 'Ver' : 'Editar') + ' Perfil de Acesso');
                        $('#modalPerfilEditar').modal('show');
                        $('#msgOperacaoEditar').text('').hide();

                        // carrega os dados do request no form
                        $('#formPerfilEditar #id').val(data.id);
                        $('#formPerfilEditar #nome').val(data.nome);
                        $('#formPerfilEditar #descricao').val(data.descricao);
                        $('#formPerfilEditar #ativo').prop('checked', (data.ativo == "SIM" ? true : false));
                        if(data.id == 1) {
                            $('#btnPerfilSalvar').hide();
                        } else {
                            $('#btnPerfilSalvar').show();
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
            * Edit record on doble click
            */
            $("#datatables-perfils tbody").delegate('tr', 'dblclick', function (e) {
                e.stopImmediatePropagation();            

                id = $(this).attr("id");

                $.ajax({
                    type: "POST",
                    url: "perfil/edit",
                    data: {"id": id},
                    dataType: 'json',
                    success: function (data) {
                        $('#modalLabel').html('Editar Destino');
                        $(".invalid-feedback").text('').hide();     
                        $('#form-group-id').show();
                        $('#editarModal').modal('show');            

                        // implementar que seja automático foreach
                        $('#id').val(data.id);
                        $('#sigla').val(data.sigla);
                        $('#descricao').val(data.descricao);
                        // $('#ativo').bootstrapToggle(data.ativo == "SIM" ? 'on' : 'off');
                    },
                    error: function (error) {
                        if (ERROR_HTTP_STATUS.has(error.status)) {
                            window.location.href = "{{ url('/login') }}";
                            return;
                        } 
                    }
                }); 

            });         
            
            /*
            * Salvar/Inserir o Perfil de Acesso
            */
            $('#btnPerfilSalvar').on("click", function (e) {
                e.stopImmediatePropagation();
                
                $(".invalid-feedback").text('').hide();    
                const formData = new FormData($('#formPerfilEditar').get(0));
                formData.append('ativo', getAtivoValue());

                $.ajax({
                    type: "POST",
                    url: "{{url("perfil/store")}}",
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function (data) {
                        $("#alert .alert-content").text('Salvou registro ID ' + data.id + ' com sucesso.');
                        $('#alert').removeClass().addClass('alert alert-success').show().delay(5000).fadeOut(1000);
                        $('#modalPerfilEditar').modal('hide');
                        $('#datatables-perfils').DataTable().ajax.reload(null, false);
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
            * Save button action
            */
            $('#btnSalvar').on("click", function (e) {
                e.stopImmediatePropagation();
                
                $(".invalid-feedback").text('').hide();    
                // var ativoValue = getAtivoValue();    
                const formData = new FormData($('#formPerfilEditar').get(0));
                // formData.append('ativo', ativoValue);

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
                        $('#modalPerfilEditar').modal('hide');
                        $('#datatables-perfils').DataTable().ajax.reload(null, false);
                    },
                    error: function (error) {
                        if (ERROR_HTTP_STATUS.has(error.status)) {
                            window.location.href = "{{ url('/login') }}";
                            return;
                        } 

                        // validator: vamos exibir todas as mensagens de erro do validador
                        // como o dataType não é JSON, precisa do responseJSON
                        $.each( error.responseJSON.errors, function( key, value ) {
                            $("#error-" + key ).text(value).show(); 
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

            $('#btnEntidadesMarcadasSalvar').on("click", function (e) {
                e.stopImmediatePropagation();

                // percorre os checkbox marcados
                // $("input:checked").each(function() {

                // percorre todos checkbox, atributo cheked nao está funcionando
                $("input:checkbox").each(function() {
                    console.log( 'Perfil_id=' + $(this).data('perfil_id')+ ' entidade_id=' + $(this).data('entidade_id') + ' checked=' + $(this).attr('checked') );
                });                

                alert('btnEntidadesMarcadasSalvar');
           
            });

            /*
            * New Record button action
            */
            $('#btnNovo').on("click", function (e) {
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

                $('#formEntity').trigger('reset');              // clean de form data
                $('#form-group-id').hide();                     // hide ID field
                $('#id').val('');                               // reset ID field
                $('#modalLabel').html('Novo Destino');          //
                $(".invalid-feedback").text('').hide();         // hide all error displayed
                $('#editarModal').modal('show');                // show modal 
            });

            // put the focus on de name field
            $('body').on('shown.bs.modal', '#editarModal', function () {
                $('#name').focus();
            })

            function getAtivoValue() {
                return $('#ativo:checked').val() ? 'SIM': 'NÃO';
            }

            /*
            * Refresh button action
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
                $('#datatables-perfils').DataTable().ajax.reload(null, false);
                $('#alert').trigger('reset').hide();
            });      

        });

    </script>    

@stop
