@extends('adminlte::page')

@section('content_header')
    <div class="row mb-2">
        <div class="m-0 text-dark col-sm-6">
            <h1></h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="/home">Home</a></li>
                <!-- <li class="breadcrumb-item ">Administração</li> -->
                <!-- <li class="breadcrumb-item">Cadastros</li> -->
                <li class="breadcrumb-item active">Organizações</li>
            </ol>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
@stop

@section('content')

    <!-- DataTables de Dados -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-4 text-left h5"><b>Cadastro da Organização Militar</b></div>
                        
                        <!--área de mensagens-->
                        <div class="col-md-5 text-left">
                            <div style="padding: 0px;  background-color: transparent;">
                                <div id="alert" class="alert alert-danger" style="margin-bottom: 0px; display: none; padding: 2px 5px 2px 5px;">
                                    <a class="close" onClick="$('.alert').hide()">&times;</a>  
                                    <div class="alert-content">Mensagem</div>
                                </div>
                            </div>                         
                        </div>
                                                
                        <div class="col-md-3 text-right">
                            <button id="btnRefresh" class="btn btn-default btn-sm btnRefresh" data-toggle="tooltip" title="Atualizar a tabela (Alt+R)">Refresh</button>
                            <button id="btnNovo"    class="btn btn-success btn-sm btnNovo" data-toggle="tooltip" title="Adicionar um novo registro (Alt+N)" >Inserir Novo</button>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <!-- compact | stripe | order-column | hover | cell-border | row-border | table-dark-->
                    <table id="datatables" class="table table-striped table-bordered table-hover table-sm compact" style="width:100%">
                        <thead></thead>
                        <tbody></tbody>
                        <tfoot></tfoot>                
                    </table>                 
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Editar Registro -->
    <div class="modal fade" id="editarModal" tabindex="-1" aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modalLabel">Modal title</h4>
                <button type="button" class="close" data-bs-dismiss="modal" data-toggle="tooltip" title="Cancelar a operação (Esc ou Alt+C)" onClick="$('#editarModal').modal('hide');">&times;</button>
            </div>
            <div class="modal-body">

                <form id="formEntity" name="formEntity"  action="javascript:void(0)" class="form-horizontal" method="post">

                        <div class="form-group" id="form-group-id">
                            <label class="form-label">ID</label>
                            <input class="form-control" value="" type="text" id="id" name="id" placeholder="" readonly>
                        </div>                         

                        <div class="form-group">
                            <label class="form-label">Sigla</label>
                            <input class="form-control" value="" type="text" id="sigla" name="sigla" placeholder="1º Btl Inf" data-toggle="tooltip" title="Digite a sigla da Organização" >
                            <div id="error-sigla" class="error invalid-feedback" style="display: none;"></div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Nome</label>
                            <input class="form-control" value="" type="text" id="nome" name="nome" placeholder="1º Batalhão de Infantaria" data-toggle="tooltip" title="Digite o Nome da Organização" >
                            <div id="error-nome" class="error invalid-feedback" style="display: none;"></div>
                        </div>
                        
                        <div class="form-group input-group-sm">
                            <label class="form-label">Descrição</label>
                            <textarea class="form-control" id="descricao" name="descricao" placeholder="1º Batalhão de Infantaria" data-toggle="tooltip" title="Informe a Descrição do Perfil de Acesso" rows="4"></textarea>
                            <div id="error-descricao" class="error invalid-feedback" style="display: none;"></div>
                        </div>

                        <div class="form-group input-group-sm">
                            <label class="form-label" data-toggle="tooltip" title="Marcar se o Perfil de Acesso está Ativo">Ativo</label>
                            <label class="switch">
                                <input type="checkbox" id="ativo" class="switch-input" data-toggle="tooltip" title="Marcar se  o Perfil de Acesso está Ativo">
                                <span class="switch-label" data-on="SIM" data-off="NÃO"></span>
                                <span class="switch-handle"></span>
                            </label>
                            <div id="error-ativo" class="error invalid-feedback" style="display: none;"></div>
                        </div>
                </form>        

            </div>
            <div class="modal-footer">
                <div class="col-md-5 text-left">
                    <label id="msgOperacao" class="error invalid-feedback" style="color: red; display: none; font-size: 12px;"></label> 
                </div>
                <div class="col-md-5 text-right">
                    <button type="button" class="btn btn-sm btn-secondary btnCancelar" data-bs-dismiss="modal" data-toggle="tooltip" title="Cancelar a operação (Esc ou Alt+C)" onClick="$('#editarModal').modal('hide');">Cancelar</button>
                    @can('is_admin')
                    {{-- <button type="button" class="btn btn-primary btnSalvar" id="btnSalvar" data-toggle="tooltip" title="Salvar o registro (Alt+S)">Salvar</button> --}}
                    @endcan
                    <button type="button" class="btn btn-sm btn-primary btnSalvar"  id="btnSalvar"  data-toggle="tooltip" title="Salvar o registro (Alt+S)">Salvar</button>
                    <button type="button" class="btn btn-sm btn-success btnInserir" id="btnInserir" data-toggle="tooltip" title="Inserir o novo registro (Alt+S)">Inserir</button>
                </div>
        </div>
            </div>
        </div>
    </div>

    <!-- modal Excluir registro -->
    <div class="modal fade" id="confirmaExcluirModal" tabindex="-1" aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Excluir Registro</h4>
                    <button type="button" class="close" data-bs-dismiss="modal" data-toggle="tooltip" title="Cancelar a operação (Esc ou Alt+C)" onClick="$('#confirmaExcluirModal').modal('hide');">&times;</button>
                </div>
                <div class="modal-body">
                    <p></p>
                    <label id="msgOperacaoExcluir" class="error invalid-feedback" style="color: red; display: none; font-size: 12px;"></label> 
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" data-toggle="tooltip" title="Cancelar a operação (Esc ou Alt+C)" onClick="$('#confirmaExcluirModal').modal('hide');">Cancelar</button>
                    <button type="button" class="btn btn-danger" data-toggle="tooltip" title="Confirmar a Exclusão" id="confirm">Excluir</button>
                </div>
            </div>
        </div>
    </div>   

    <!-- script de comportamento da página -->
    <script type="text/javascript">

        // métodos HTTP em Laravel para API
        // GET: Usado para recuperar dados.
        // POST: Usado para criar novos recursos.
        // PUT: Usado para atualizar um recurso completamente.
        // PATCH: Usado para atualizar parcialmente um recurso.
        // DELETE: Usado para deletar um recurso.
        // OPTIONS: Usado para obter as opções de comunicação de um recurso.    

        $(document).ready(function () {

            var id = '';

            // validar o X-CSRF-TOKEN
            $.ajaxSetup({
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                statusCode: { 401: function() { window.location.href = "/";} }
            });

            /*
            * Lista a tabela de dados de registros
            */
            $('#datatables').DataTable({
                serverSide: false,          // carrega todos os registro de uma vez                                                   
                processing: true,           // chama cortina customizada do DataTable
                responsive: true,           
                autoWidth: true,
                // order: [ 0, 'desc' ],    // mantém a ordem vinda do BD
                lengthMenu: [[5, 10, 15, 30, 50, -1], [5, 10, 15, 30, 50, "Todos"]], 
                language: { url: "{{ asset('vendor/datatables/DataTables.pt_BR.json') }}" },
                ajax: {
                    url: "https://apieventos.voluntary.com.br/api/organizacao",     // consumindo API externa
                    dataSrc: "",                                                    // dispensa aaData[]
                    headers: {
                        'contentType': 'application/json',                          // Tipo de conteúdo de envio (JSON)
                        'Authorization': 'Bearer 19|HUm7ao29lyhtDjo9BGKwL8DbbbNl22i79cIvURn07c5bfa08',
                    },                     
                },                
                columns: [
                    {"data": "id", "name": "organizacaos.id", "class": "text-center", "title": "#", "width": "20px"},
                    {"data": "sigla", "name": "organizacaos.sigla", "class": "text-left", "title": "Sigla", "width": "100px",
                        render: function (data) { return '<b>' + data + '</b>';}
                    },
                    {"data": "nome", "name": "organizacaos.nome", "class": "text-left", "title": "Nome", "width": "40%px"},
                    {"data": "descricao", "name": "organizacaos.descricao", "class": "text-left", "title": "Descrição", "width": "auto"},
                    {"data": "ativo", "name": "organizacaos.ativo", "class": "text-center", "title": "Ativo", "width": "30px",  
                        render: function (data) { return '<span class="' + ( data == 'SIM' ? 'text-primary' : 'text-danger') + '">' + data + '</span>';}
                    },
                    {"data": "id", "botoes": "", "orderable": false, "class": "text-center", "title": "Ações", "width": "100px", 
                        render: function (data, type) { 
                            return '<button class="btnExcluir btn btn-danger btn-xs" data-toggle="tooltip" title="Excluir o registro atual">Excluir</button> <button class="btnEditar  btn btn-primary btn-xs" data-toggle="tooltip" title="Editar o registro atual">Editar</button>'; 
                        }
                    },
                ]
            });

            /*
            * Abrir o form em branco
            */
            $('#btnNovo').on("click", function (e) {
                e.stopImmediatePropagation();

                $('#editarModal #form-group-id').hide();                            // esconde o ID
                $('#editarModal #btnInserir').show();                               // mostra o botão Inserir
                $('#editarModal #btnSalvar').hide();                                // esconde o botão Salvar
                $('#formEntity').trigger("reset");                                  // limpa mensagens de erro
                $('#editarModal #modalLabel').html('Inserir nova Organização');     // título do modal
                $(".invalid-feedback").text('').hide();                             // hide all error displayed
                $('#formEntity #ativo').prop('checked', true);                      // default SIM
                $('#editarModal').modal('show');                                    // show modal 
            });              

            /*
            * Editar um registro
            */
            $("#datatables tbody").delegate('tr td .btnEditar', 'click', function (e) {
                e.stopImmediatePropagation();            

                const id = $(this).parents('tr').attr("id");

                // GET via AJAX usado usado para recuperar um recurso existente
                $.ajax({
                    type: "GET",
                    url: "https://apieventos.voluntary.com.br/api/organizacao/" + id,   // consumindo API externa via GET
                    headers: {
                        'Content-Type': 'application/json',  
                        'Authorization': 'Bearer 19|HUm7ao29lyhtDjo9BGKwL8DbbbNl22i79cIvURn07c5bfa08',
                    },
                    dataType: 'json',
                    success: function (data) {
                        // console.log(data);
                        $('#modalLabel').html('Editar Organização');
                        $(".invalid-feedback").text('').hide();         // hide and clen all erros messages on the form
                        $('#editarModal #btnInserir').hide();            // esconde o botão Salvar
                        $('#editarModal #btnSalvar').show();            // esconde o botão Salvar
                        $('#form-group-id').show();
                        $('#editarModal').modal('show');                // show the modal

                        // implementar que seja automático foreach   
                        $('#id').val(data.id);
                        $('#sigla').val(data.sigla);
                        $('#nome').val(data.nome);
                        $('#descricao').val(data.descricao);
                        $('#formEntity #ativo').prop('checked', (data.ativo == "SIM" ? true : false));
                    },
                    error: function (error) {
                        if (error.statusText === 'Unauthenticated') {
                            window.location.href = "{{ url('/') }}";
                        }
                    }
                }); 

            });           

            /*
            * Excluir um registro
            */
            $("#datatables tbody").delegate('tr td .btnExcluir', 'click', function (e) {
                e.stopImmediatePropagation();            

                id = $(this).parents('tr').attr("id");

                // abre Form Modal Bootstrap e pede confirmação da Exclusão do Registro
                $('#msgOperacaoExcluir').text('');
                $("#confirmaExcluirModal .modal-body p").text('').text('Você está certo que deseja Excluir este registro ID: ' + id + '?');
                $('#confirmaExcluirModal').modal('show');

                // se confirmar a Exclusão, exclui o Registro via Ajax
                $('#confirmaExcluirModal').find('.modal-footer #confirm').on('click', function (e) {
                    e.stopImmediatePropagation();

                    // DELETE via AJAX usado usado para excluir um recurso existente
                    $.ajax({
                        type: "DELETE",
                        url: "https://apieventos.voluntary.com.br/api/organizacao/" + id,   // consumindo API externa via DELETE
                        headers: {
                            'Content-Type': 'application/json',  
                            'Authorization': 'Bearer 19|HUm7ao29lyhtDjo9BGKwL8DbbbNl22i79cIvURn07c5bfa08',
                        },
                        dataType: 'json',
                        success: function (data) {
                            $("#alert .alert-content").text('Excluiu o registro ID ' + id + ' com sucesso.');
                            $('#alert').removeClass().addClass('alert alert-success').show().delay(5000).fadeOut(1000);
                            $('#confirmaExcluirModal').modal('hide');
                            $('#datatables').DataTable().ajax.reload(null, false);  
                        },
                        error: function (error) {
                            if (error.statusText === 'Unauthenticated') {
                                window.location.href = "{{ url('/') }}";
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
            * Inserir um novo registro
            */
            $('#btnInserir').on("click", function (e) {
                e.stopImmediatePropagation();

                // cria um objeto FormData tipo 'multipart/form-data' a partir do name dos inputs. É necesario usar .get(0)
                const formDados = new FormData($('#formEntity').get(0));
                formDados.append('ativo', getAtivoValue());              // processa o campo Ativo separadamente

                // como a API espera um JSON, vamos converter o FormData de 'multipart/form-data' para JSON
                let formDataJSON = {};
                formDados.forEach((value, key) => {
                    formDataJSON[key] = value;
                });                

                // POST via AJAX usado para inserir um registro em um recurso existente.
                $.ajax({
                    type: "POST",
                    url: "https://apieventos.voluntary.com.br/api/organizacao",
                    headers: {
                        'Content-Type': 'application/json',  
                        'Authorization': 'Bearer 19|HUm7ao29lyhtDjo9BGKwL8DbbbNl22i79cIvURn07c5bfa08',
                    },     
                    dataType: 'json',               
                    data: JSON.stringify(formDataJSON),            // para transformar os dados em uma string JSON antes de enviar
                    processData: false,
                    success: function (response) {
                        $("#alert .alert-content").text('Inseriu o registro ID ' + response.id + ' com sucesso.');
                        $('#alert').removeClass().addClass('alert alert-success').show().delay(5000).fadeOut(1000);
                        $('#editarModal').modal('hide');
                        $('#datatables').DataTable().ajax.reload(null, false);
                    },
                    error: function (error) {
                        if (error.statusText === 'Unauthenticated') {
                            window.location.href = "{{ url('/') }}";
                        }                        
                        // validator: vamos exibir todas as mensagens de erro do validador
                        $.each( error.responseJSON.errors, function( key, value ) {
                            $("#error-" + key ).text(value).show(); 
                        });
                        $('#msgOperacao').text(error.responseJSON.message).show();
                    }
                });                   
            });              

            /*
            * Salvar o registro em edição
            */
            $('#btnSalvar').on("click", function (e) {
                e.stopImmediatePropagation();

                // cria um objeto FormData tipo 'multipart/form-data' a partir do name dos inputs. É necesario usar .get(0)
                const formDados = new FormData($('#formEntity').get(0));
                formDados.append('ativo', getAtivoValue());              // process o campo Ativo separadamente

                // como a API espera um JSON, vamos converter o FormData de 'multipart/form-data' para JSON
                let formDataJSON = {};
                formDados.forEach((value, key) => {
                    formDataJSON[key] = value;
                });                

                // PUT via AJAX usado para atualizar completamente um recurso existente. 
                $.ajax({
                    type: "PUT",
                    url: "https://apieventos.voluntary.com.br/api/organizacao/" + formDados.get('id'),   
                    headers: {
                        'Content-Type': 'application/json',  
                        'Authorization': 'Bearer 19|HUm7ao29lyhtDjo9BGKwL8DbbbNl22i79cIvURn07c5bfa08',
                    },     
                    dataType: 'json',               
                    data: JSON.stringify(formDataJSON),            // para transformar os dados em uma string JSON antes de enviar
                    processData: false,
                    success: function (response) {
                        $("#alert .alert-content").text('Salvou registro ID ' + response.id + ' com sucesso.');
                        $('#alert').removeClass().addClass('alert alert-success').show().delay(5000).fadeOut(1000);
                        $('#editarModal').modal('hide');
                        $('#datatables').DataTable().ajax.reload(null, false);
                    },
                    error: function (error) {
                        if (error.statusText === 'Unauthenticated') {
                            window.location.href = "{{ url('/') }}";
                        }
                        // validator: vamos exibir todas as mensagens de erro do validador
                        $.each( error.responseJSON.errors, function( key, value ) {
                            $("#error-" + key ).text(value).show(); 
                        });
                        $('#msgOperacao').text(error.responseJSON.message).show();
                    }
                });   
            });

            // por o foco no primeiro campo do modal
            $('body').on('shown.bs.modal', '#editarModal', function () {
                $('#nome').focus();
            })

            /*
            * Atualizar a tabela de dados de registros
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
                $('#datatables').DataTable().ajax.reload(null, false);    
                $('#alert').trigger('reset').hide();
            });        

            /*
            * Usado para converter o checkbox Ativo
            */
            function getAtivoValue() {
                if ($('input[id="ativo"]:checked').val()) {
                    return 'SIM';
                } else {
                    return 'NÃO';
                }
            }

        });

    </script>    

@stop
