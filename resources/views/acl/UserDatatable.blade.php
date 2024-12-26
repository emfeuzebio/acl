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

    <style>

        .dt-center {
            text-align: center !important;
        }       

        .switch-input:disabled {
            background-color: #99c1d5 !important; /* A cor de fundo desejada */
            cursor: not-allowed; /* O cursor é alterado para indicar que o input está desabilitado */        
        }

        /* Aumenta o z-index do segundo modal */
        .modal.fade {
        z-index: 1050; /* z-index padrão do Bootstrap 4 */
        }

        #modal2.modal.fade.show {
        z-index: 1060; /* Maior z-index para garantir que o modal 2 sobreponha o modal 1 */
        }        

    </style>    

<!-- Custom CSS para efeito de Hover nas guias -->
<style>
  .custom-tabs-hover .nav-item {
    border: 1px solid #ddd;
    border-radius: 5px 5px 0 0;
    margin-right: 5px;
  }

  .custom-tabs-hover .nav-link {
    border: none;
    border-radius: 5px 5px 0 0;
    padding: 10px 15px;
    color: #007bff;
    background-color: #f8f9fa;
    transition: all 0.3s ease;
  }

  .custom-tabs-hover .nav-link.active {
    color: #fff;
    background-color: #007bff;
    border: 1px solid #007bff;
  }

  .custom-tabs-hover .nav-link:hover {
    background-color: #e9ecef;
    border-color: #007bff;
  }

  .tab-content {
    border: 1px solid #ddd;
    border-radius: 0 0 5px 5px;
    padding: 20px;
    background-color: #f9f9f9;
  }
</style>



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
    
    <!-- Modal -->
    <div class="modal fade" id="modalPerfilVer" tabindex="-1" role="dialog" aria-labelledby="modalExemploLabel" aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalLabel">Detalhes do Perfil de Acesso</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs custom-tabs-hover" id="myTab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="perfil-tab" data-toggle="tab" href="#perfil" role="tab" aria-controls="perfil" aria-selected="true">Detalhes</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="autorizacoes-tab" data-toggle="tab" href="#autorizacoes" role="tab" aria-controls="autorizacoes" aria-selected="false">Autorizações</a>
                    </li>
                    </ul>
                    <!-- Tab panes -->
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="perfil" role="tabpanel" aria-labelledby="perfil-tab">


                            <form id="formPerfilEditar" name="formPerfilEditar" action="javascript:void(0)" class="form-horizontal" method="post">

                                <div class="form-group input-group-sm" id="form-group-id">
                                    <label class="form-label">ID</label>
                                    <input class="form-control" value="" type="text" id="id" name="id" placeholder="" readonly data-toggle="tooltip" title="ID do Perfil de Acesso">
                                </div>

                                <div class="form-group input-group-sm">
                                    <label class="form-label">Nome</label>
                                    <input class="form-control" disabled value="" type="text" id="nome" name="nome" placeholder="" data-toggle="tooltip" title="Informe o Nome do Perfil de Acesso" >
                                    <div id="error-nome" class="error invalid-feedback" style="display: none;"></div>
                                </div>

                                <div class="form-group input-group-sm">
                                    <label class="form-label">Descrição</label>
                                    <textarea class="form-control" disabled id="descricao" name="descricao" placeholder="" data-toggle="tooltip" title="Informe a Descrição do Perfil de Acesso" rows="4"></textarea>
                                    <div id="error-descricao" class="error invalid-feedback" style="display: none;"></div>
                                </div>

                                <div class="form-group input-group-sm">
                                    <label class="form-label" data-toggle="tooltip" title="Marcar se o Perfil de Acesso está Ativo">Ativo</label>
                                    <label class="switch">
                                        <input type="checkbox" disabled id="ativo" name="ativo" class="switch-input" data-toggle="tooltip" title="Marcar se  o Perfil de Acesso está Ativo">
                                        <span class="switch-label" data-on="SIM" data-off="NÃO"></span>
                                        <span class="switch-handle"></span>
                                    </label>
                                    <div id="error-ativo" class="error invalid-feedback" style="display: none;"></div>
                                </div>

                            </form>
                        
                    </div>  
                    <div class="tab-pane fade" id="autorizacoes" role="tabpanel" aria-labelledby="autorizacoes-tab">
                        
                            <fieldset class="border p-2">
                                <legend class="w-auto h5">Ações Autorizadas</legend>

                                <input type="hidden" id="perfil_id" value="">
                                <table id="tblAutorizacoes" class="table table-striped table-bordered table-hover table-sm compact" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Ação</th>
                                            <th>Rota</th>
                                            <th>Autorizada</th>
                                        </tr>
                                    </thead>
                                    <tbody id="bodyAutorizacoes">
                                        <tr>
                                            <td></td>
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
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">

        const ERROR_HTTP_STATUS = new Set([401, 419]); // 401-UNAUTHORIZED, 403-FORBIDDEN, 419-PAGE_EXPIRED, 404-NOT_FOUND, 500-INTERNAL_SERVER_ERROR

        $(document).ready(function () {

            let id = '';
            let perfil_id = '';
            var autorizacoes = '';

            $.ajaxSetup({
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },  // valida o X-CSRF-TOKEN
                statusCode: { 401: function() { window.location.href = "/login";} },        // 401-UNAUTHORIZED redireciona para login
            });

            /*
            * Cria o datatables de Usuários
            */
            $('#datatables-users').DataTable({
                processing: true,
                // serverSide: true,
                responsive: true,
                autoWidth: true,
                // order: [ 1, 'asc' ],
                lengthMenu: [[5, 10, 15, 30, 50, -1], [5, 10, 15, 30, 50, "Todos"]], 
                pageLength: 10,
                ajax: {
                    type: "GET",
                    url: "{{url("user")}}",                             // rota
                    dataSrc: function (json) {
                        let autorizacoes = json.autorizacoes;           // Rotas autorizadas
                        // console.log(autorizacoes);                   // Rotas autorizadas

                        // controle do botão Inserir Novo
                        if (json.autorizacoes.includes('user.store')) { $("#btnInserirNovo").show(); } else { $("#btnInserirNovo").hide(); }

                        // controle do botão Salvar do Modal de Edição
                        if (json.autorizacoes.includes('user.update')) { $("#btnUserSalvar").show(); } else { $("#btnUserSalvar").hide(); }

                        return json.data;                           // Retorna lista de dados para o DataTables
                    },                    
                },
                // language: { url: "{{ asset('vendor/datatables/DataTables.pt_BR.json') }}" },
                columns: [
                    {"data": "id", "name": "users.id", "class": "dt-right", "title": "#", "width": "30px"},
                    {"data": "name", "name": "users.name", "class": "dt-left", "title": "Nome", "width": "150px",
                        render: function (data) { return '<b>' + data + '</b>';}},
                    {"data": "email", "name": "users.email", "class": "dt-left", "title": "E-Mail", "width": "auto"},
                    {"data": "perfis", "name": "users.perfis", "searchable":false, "orderable": false, "class": "dt-left", "title": "Perfis de Acesso", "width": "auto",
                        render: function(data, type, row, json) {

                            var btnConcederPerfil = '';
                            var listarPerfis = '';

                            // controle botão btnConcederPerfil
                            if (row.autorizacoes.includes('user.concederPerfil')) {
                                btnConcederPerfil = '<button class="btn btn-xs btn-success btnConcederPerfil" data-toggle="tooltip" title="Conceder Perfis de Acesso ao Usuário">Conceder</button> ';
                            }                            

                            // controla a lista listarPerfis
                            if (row.autorizacoes.includes('user.listarPerfis')) {
                                listarPerfis = $.map(data, function(d, i) {
                                    return '<button id="' + d.id + '" class="btn btn-xs btn-' + ( d.id <= 1 ? 'default' : 'info btnPerfilVer' ) + '" data-perfil_id="' + d.id + '" data-toggle="tooltip" title="' + ( d.id <= 1 ? 'Entidade Padrão não pode ser editada.' : 'Administrar as Permissões concedidas à Entidade' ) + ' (' + d.nome + ')">' + d.nome + '</button> ';
                                }).join(' ');
                            }
                            
                            return btnConcederPerfil + listarPerfis;
                            
                    }},
                    // {"data": "created_at", "name": "users.created_at", "class": "dt-center", "title": "Criado em", "width": "130px",
                    //     render: function (data) { return new Date(data).toLocaleString('pt-BR'); }
                    // },
                    {"data": "updated_at", "name": "users.updated_at", "title": "Atualizado em", "width": "130px", 
                        render: function (data) { return new Date(data).toLocaleString([], {day: "2-digit",month: "2-digit",year: "numeric",hour: "2-digit",minute: "2-digit"}); }
                    },
                    {"data": "id", "botoes": "", "orderable": false, "class": "dt-center", "title": "Ações", "width": "80px", 
                        render: function (data, type, row) { 

                            btnEditar = '';                 // esconde botoes
                            btnExcluir = '';                // esconde botoes

                            // controle botão Ver
                            if (row.autorizacoes.includes('user.show')) {
                                btnEditar = '<button type="button" class="btnUserEditar btn btn-primary btn-xs" data-operacao="ver" data-toggle="tooltip" title="Ver o registro atual">Ver</button> ';
                            }

                            // // controle botão Editar
                            if (row.autorizacoes.includes('user.update')) {
                                btnEditar = '<button type="button" class="btnUserEditar btn btn-primary btn-xs" data-operacao="salvar" data-toggle="tooltip" title="Editar o registro atual">Editar</button> ';
                            }

                            // // controle botão Excluir
                            if (row.autorizacoes.includes('user.destroy')) {
                                btnExcluir = '<button type="button" class="btnUserExcluir btn btn-danger btn-xs" data-operacao="excluir" data-toggle="tooltip" title="Excluir o registro atual">Excluir</button> ';
                            }

                            return btnEditar + btnExcluir; 

                            // As Estidades Básica (id=[1-5]) não podem ser excluídas
                            // return ( row.id > 1 ? '<button class="btnUserEditar btn btn-primary btn-xs" data-toggle="tooltip" title="Editar o registro atual">Editar</button> ' : 
                            //                       '<button class="btnUserEditar btn btn-default btn-xs" data-toggle="tooltip" title="Ver o registro atual">Ver</button>' )  + 
                            //        ( row.id > 1 ? '<button class="btnUserExcluir btn btn-danger btn-xs" data-toggle="tooltip" title="Excluir o registro atual">Excluir</button> ' : ' ' ) +
                            //        ''; 
                            //    '<button class="btnRotas btn btn-info btn-xs" data-toggle="tooltip" title="Editar as Rotas da Entidade atual">Perfis</button> '; 
                        }
                    },
                ]
            });

            /*
            * Editar o Usuário
            */
            $("#datatables-users tbody").delegate('tr td .btnPerfilVer', 'click', function (e) {
                e.stopImmediatePropagation();           
                
                var perfil_id = $(this).data("perfil_id");

                $.ajax({
                    type: "GET",
                    url: "{{url("perfil/show")}}",
                    data: { "id": perfil_id},
                    dataType: 'json',
                    success: function (response) {

                        // monta guia detalhes do Perfil
                        $('#modalPerfilVer #modalLabel').html('Ver o Perfil de Acesso');
                        $('#modalPerfilVer #id').val(response.id);
                        $('#modalPerfilVer #nome').val(response.nome);
                        $('#modalPerfilVer #descricao').val(response.descricao);
                        $('#modalPerfilVer #ativo').prop('checked', (response.ativo == "SIM" ? true : false));

                        $('#tblAutorizacoes').DataTable().destroy();  // Destrói a instância existente
                        $('#minhaTabela tbody').empty();

                        // monta guia tabela de autorizações
                        tblAutorizacoesLinhas = '';
                        $.each(response.autorizacoes, function(i, obj){
                            tblAutorizacoesLinhas += '' + 
                            '<tr id="tr' + obj.id + '">' + 
                                '<td>' + (i+1) + '</td>' + 
                                '<td>' + obj.rota.descricao + '</td>' + 
                                '<td>' + obj.rota.rota + '</td>' + 
                                '<td class="text-center">' + 
                                    '<label class="switch">' + 
                                    // '<input type="checkbox" id="' + obj.id + '" data-perfil_id="' + perfil_id + '" data-rota_id="' + obj.id + '" ' + ( obj.ativo == 'SIM' ? 'checked' : '' ) + '  class="switch-input" data-toggle="tooltip" title="Incluir">' + 
                                    '<input type="checkbox" id="' + obj.id + '" data-autorizacao_id="' + obj.id + '" ' + ( obj.ativo == 'SIM' ? 'checked' : '' ) + '  class="switch-input" data-toggle="tooltip" title="Incluir">' + 
                                    '<span class="switch-label" data-on="SIM" data-off="NÃO"></span>' + 
                                    '<span class="switch-handle"></span>' + 
                                    '</label>' + 
                                '</td>' + 
                            '</tr>';
                        })
                        tblAutorizacoesLinhas = tblAutorizacoesLinhas ? tblAutorizacoesLinhas : '<tr><td class="text-center" colspan="4">Nenhuma Autorização encontrada</td></tr>';
                        $('#bodyAutorizacoes').empty().append(tblAutorizacoesLinhas);    // adiciona as linhas na tabela   

                        // Renderiza o DataTable com os dados populados
                        $('#tblAutorizacoes').DataTable({
                            lengthMenu: [[5, 10, 15, 30, 50, -1], [5, 10, 15, 30, 50, "Todos"]], 
                            pageLength: 5,
                            autoWidth: true,
                        });
                        $('#modalPerfilVer').modal('show');                             // show modal  
                    },
                    error: function (error) {
                        if (ERROR_HTTP_STATUS.has(error.status)) { window.location.href = "{{ url('/login') }}"; return; } 
                        $('#alertModal .modal-body').text(error.responseJSON.message)
                        $('#alertModal').modal('show');
                    }
                }); 
            });


            /**
             * ações ao clicar sobre os checkbox e mudar seu estado: tr td :checkbox
             */
            $("#tblAutorizacoes tbody").delegate('tr td :checkbox', 'click', function (e) {
                e.stopImmediatePropagation();                 

                var chkObjeto = $(this).attr("id");
                var autorizacao_id = $(this).data("autorizacao_id");
                var ativo     = $(this).is(":checked") ? "SIM" : "NÃO";

                $.ajax({
                    type: "POST",
                    url: "{{url("autorizacao/authorizar")}}",
                    data: { "autorizacao_id":autorizacao_id, "ativo":ativo },
                    // data: { "perfil_id":perfil_id, "rota_id":rota_id, "ativo":ativo },
                    dataType: 'json',
                    success: function (data) {
                        // nesse caso sucesso não precisa exibir confirmação por ser um switchbox
                        // console.log(data)
                    },
                    error: function (error) {
                        // alert('error');
                        if (ERROR_HTTP_STATUS.has(error.status)) {
                            window.location.href = "{{ url('/login') }}";
                            return;
                        } 

                        // retorna o checkbox para o estado anterior
                        $('#'+chkObjeto).prop('checked', (ativo == 'SIM' ? false : true));               
                        $('#alertModal .modal-body').text(error.responseJSON.message)
                        $('#alertModal').modal('show');   
                    }
                });                 
               
            });               

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
                        // if(data.id == 1) {
                        //     $('#btnUserSalvar').hide();
                        // } else {
                        //     $('#btnUserSalvar').show();
                        // }
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

<style>

    /* Aumenta o z-index do segundo modal */
    .modal.fade {
    z-index: 1050; /* z-index padrão do Bootstrap 4 */
    }

    #alertModal.modal.fade.show {
    z-index: 1060; /* Maior z-index para garantir que o modal 2 sobreponha o modal 1 */
    }        

</style>    