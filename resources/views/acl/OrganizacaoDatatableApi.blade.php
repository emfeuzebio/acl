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
                            <button id="btnNovo" class="btnNovo btn btn-success btn-sm" data-toggle="tooltip" title="Adicionar um novo registro (Alt+N)" >Inserir Novo</button>
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

                <form id="formEntity" name="formEntity"  action="javascript:void(0)" 
                    class="form-horizontal" method="post">

                        <div class="form-group" id="form-group-id">
                            <label class="form-label">ID</label>
                            <input class="form-control" value="" type="text" id="id" name="id" placeholder="" readonly>
                        </div>                         
                        
                        <div class="form-group">
                            <label class="form-label">Nome</label>
                            <input class="form-control" value="" type="text" id="nome" name="nome" placeholder="1º Batalhão de Infantaria" data-toggle="tooltip" title="Digite o Nome da Organização" >
                            <div id="error-nome" class="error invalid-feedback" style="display: none;"></div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Sigla</label>
                            <input class="form-control" value="" type="text" id="sigla" name="sigla" placeholder="1º Btl Inf" data-toggle="tooltip" title="Digite a sigla da Organização" >
                            <div id="error-sigla" class="error invalid-feedback" style="display: none;"></div>
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
                    <button type="button" class="btn btn-secondary btn-sm btnCancelar" data-bs-dismiss="modal" data-toggle="tooltip" title="Cancelar a operação (Esc ou Alt+C)" onClick="$('#editarModal').modal('hide');">Cancelar</button>
                    @can('is_admin')
                    <button type="button" class="btn btn-primary btn-sm btnSalvar" id="btnSalvar" data-toggle="tooltip" title="Salvar o registro (Alt+S)">Salvar</button>
                    @endcan
                    <button type="button" class="btn btn-primary btn-sm btnSalvar" id="btnSalvar" data-toggle="tooltip" title="Salvar o registro (Alt+S)">Salvar</button>
                    <button type="button" class="btn btn-success btn-sm btnInserir" id="btnInserir" data-toggle="tooltip" title="Inserir o novo registro (Alt+S)">Inserir</button>
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

        $(document).ready(function () {

            var id = '';

            // valida o X-CSRF-TOKEN
            $.ajaxSetup({
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                statusCode: { 401: function() { window.location.href = "/";} }
            });

            /*
            * Lista a tabela de dados de registros
            */
            $('#datatables').DataTable({
                // serverSide: true,
                processing: true,
                responsive: true,
                autoWidth: true,
                // order: [ 0, 'desc' ],
                lengthMenu: [[5, 10, 15, 30, 50, -1], [5, 10, 15, 30, 50, "Todos"]], 
                // ajax: "organizacao",
                ajax: {
                    url: "https://apieventos.voluntary.com.br/api/organizacao",     // consumindo API externa
                    // url: "organizacao",                                          // chamado controller na app
                    dataSrc: "",                                                    // dispensa aaData[]
                    headers: {
                        'contentType': 'application/json',                          // Tipo de conteúdo de envio (JSON)
                        'Authorization': 'Bearer 19|HUm7ao29lyhtDjo9BGKwL8DbbbNl22i79cIvURn07c5bfa08',
                    },                     
                },                
                language: { url: "{{ asset('vendor/datatables/DataTables.pt_BR.json') }}" },
                columns: [
                    {"data": "id", "name": "organizacaos.id", "class": "dt-right", "title": "#"},
                    {"data": "nome", "name": "organizacaos.nome", "class": "dt-left", "title": "Nome",
                        render: function (data) { return '<b>' + data + '</b>';}},
                    {"data": "sigla", "name": "organizacaos.sigla", "class": "dt-left", "title": "Sigla"},
                    {"data": "descricao", "name": "organizacaos.descricao", "class": "dt-left", "title": "Descrição"},
                    {"data": "ativo", "name": "organizacaos.ativo", "class": "dt-center", "title": "Ativo",  
                        render: function (data) { return '<span class="' + ( data == 'SIM' ? 'text-primary' : 'text-danger') + '">' + data + '</span>';}
                    },
                    {"data": "id", "botoes": "", "orderable": false, "class": "dt-center", "title": "Ações", 
                        render: function (data, type) { 
                            return '<button data-id="' + data.id + '" class="btnExcluir btn btn-danger btn-xs" data-toggle="tooltip" title="Excluir o registro atual">Excluir</button><button data-id="' + data.id + '" class="btnEditar  btn btn-primary btn-xs" data-toggle="tooltip" title="Editar o registro atual">Editar</button>'; 
                        }
                    },
                ]
            });

            /*
            * Inserir um Novo registro
            */
            $('#btnNovo').on("click", function (e) {
                e.stopImmediatePropagation();

                $('#editarModal #form-group-id').hide();                            // esconde o ID
                $('#formEntity').trigger("reset");                                  // limpa mensagens de erro
                $('#editarModal #modalLabel').html('Inserir nova Organização');     // título do modal
                $(".invalid-feedback").text('').hide();                             // hide all error displayed
                $('#formEntity #ativo').prop('checked', true);                      // default SIM
                $('#editarModal').modal('show');                                    // show modal 
                $('#btnPerfilSalvar').show();
                // $('#editarModal #model').focus();
            });              

            /*
            * Editar um registro
            */
            $("#datatables tbody").delegate('tr td .btnEditar', 'click', function (e) {
                e.stopImmediatePropagation();            

                const id = $(this).parents('tr').attr("id");

                $.ajax({
                    // type: "POST",                                                    // chamado controller na app via POST
                    // url: "organizacao/edit",                                         
                    // data: { "id": id },
                    type: "GET",
                    url: "https://apieventos.voluntary.com.br/api/organizacao/" + id,   // consumindo API externa via GET
                    dataType: 'json',
                    success: function (data) {
                        // console.log(data);
                        $('#modalLabel').html('Editar Organização');
                        $(".invalid-feedback").text('').hide();     //hide and clen all erros messages on the form
                        $('#form-group-id').show();
                        $('#editarModal').modal('show');         //show the modal

                        // implementar que seja automático foreach   
                        $('#id').val(data.id);
                        $('#sigla').val(data.sigla);
                        $('#nome').val(data.nome);
                        $('#descricao').val(data.descricao);
                        $('#formEntity #ativo').prop('checked', (data.ativo == "SIM" ? true : false));
                    },
                    error: function (error) {
                        if (error.responseJSON === 401 || error.responseJSON.message && error.statusText === 'Unauthenticated') {
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

                const id = $(this).parents('tr').attr("id");

                //abre Form Modal Bootstrap e pede confirmação da Exclusão do Registro
                $('#msgOperacaoExcluir').text('');
                $("#confirmaExcluirModal .modal-body p").text('').text('Você está certo que deseja Excluir este registro ID: ' + id + '?');
                $('#confirmaExcluirModal').modal('show');

                //se confirmar a Exclusão, exclui o Registro via Ajax
                $('#confirmaExcluirModal').find('.modal-footer #confirm').on('click', function (e) {
                    e.stopImmediatePropagation();

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

                // to use a button as submit button, is necesary use de .get(0) after
                const formDados = new FormData($('#formEntity').get(0));
                formDados.append('ativo', 'SIM');                                   // add Ativo field

                axios.post('https://apieventos.voluntary.com.br/api/organizacao', 
                    formDados, {
                        headers: {
                            'Content-Type': 'application/json',  
                            'Authorization': 'Bearer 19|HUm7ao29lyhtDjo9BGKwL8DbbbNl22i79cIvURn07c5bfa08',
                        }
                }).then(response => {

                    // sucesso: a API retorna um JSON com os dados do novo registro dentro de response.data
                    $("#alert .alert-content").text('Registro ID ' + response.data.id + ' inserido com sucesso.');
                    $('#alert').removeClass().addClass('alert alert-success').show().delay(5000).fadeOut(1000);
                    $('#editarModal').modal('hide');
                    $('#datatables').DataTable().ajax.reload(null, false);
                }).catch(error => {

                    // Servidor respondeu com um código de status que não está na faixa 2xx
                    if (error.response) {

                        // Array de erros (pode ser status ou mensagens)
                        const arrStatusErros = [400, 403, 404, 405, 500, 400, 'erro_de_conexao'];

                        // trata todos erros de response.status
                        if (arrStatusErros.includes(error.response.status)) {
                            $('#msgOperacao').text('[' + error.response.status + '] - Ocorreu um erro ao processar a requisição: ' + error.response.data.message).show();
                            return;
                        }

                        // vamos exibir todas as mensagens de erro específicas de validação abaixo de cada campo correspondente
                        $.each( error.response.data.errors, function( key, value ) {
                            $("#error-" + key ).text(value).show(); 
                        });
                        $('#msgOperacao').text(error.response.data.message).show();                 // mensagem geral
                        
                    } else if (error.request) {
                        // A requisição foi feita, mas não houve resposta
                        $('#msgOperacao').text('error.request: o Servidor não respondeu à requisição. Verifique sua rede"').show(); 
                    } else {
                        // Algo aconteceu ao configurar a requisição
                        $('#msgOperacao').text('error.desconhecido: ' +error.message).show();       // mensagem geral
                    }                    
                });  


                // $.ajax({
                //     type: "POST",
                //     // url: "organizacao/store",
                //     // data: formDados,
                //     url: "https://apieventos.voluntary.com.br/api/organizacao/",   // consumindo API externa via GET
                //     headers: {
                //         'contentType': 'application/json',                          // Tipo de conteúdo de envio (JSON)
                //         'Access-Control-Allow-Origin': '',                        
                //         'Authorization': 'Bearer 19|HUm7ao29lyhtDjo9BGKwL8DbbbNl22i79cIvURn07c5bfa08',
                //     },     
                //     dataType: 'json',               
                //     data: formDados,  
                //     // data: JSON.stringify(formDados),  

                //     cache: false,
                //     contentType: false,
                //     processData: false,
                //     success: function (data) {
                //         $("#alert .alert-content").text('Salvou registro ID ' + data.id + ' com sucesso.');
                //         $('#alert').removeClass().addClass('alert alert-success').show().delay(5000).fadeOut(1000);
                //         $('#editarModal').modal('hide');
                //         $('#datatables').DataTable().ajax.reload(null, false);
                //     },
                //     error: function (error) {
                //         if (error.responseJSON === 401 || error.responseJSON.message && error.statusText === 'Unauthenticated') {
                //             window.location.href = "{{ url('/') }}";
                //         }
                //         // validator: vamos exibir todas as mensagens de erro do validador
                //         // como o dataType não é JSON, precisa do responseJSON
                //         $.each( error.responseJSON.errors, function( key, value ) {
                //             $("#error-" + key ).text(value).show(); //show all error messages
                //         });
                //         $('#msgOperacao').text(error.responseJSON.message).show();
                //     }
                // });                
            });

            /*
            * Salvar o registro em edição
            */
            $('#btnSalvar').on("click", function (e) {
                e.stopImmediatePropagation();

                // to use a button as submit button, is necesary use de .get(0) after
                const formDados = new FormData($('#formEntity').get(0));
                formDados.append('ativo', 'SIM');                                   // add Ativo field

                axios.patch('https://apieventos.voluntary.com.br/api/organizacao', 
                    formDados, {
                        headers: {
                            'Content-Type': 'application/json',  
                            'Authorization': 'Bearer 19|HUm7ao29lyhtDjo9BGKwL8DbbbNl22i79cIvURn07c5bfa08',
                        }
                }).then(response => {

                    // sucesso: a API retorna um JSON com os dados do novo registro dentro de response.data
                    $("#alert .alert-content").text('Registro ID ' + response.data.id + ' inserido com sucesso.');
                    $('#alert').removeClass().addClass('alert alert-success').show().delay(5000).fadeOut(1000);
                    $('#editarModal').modal('hide');
                    $('#datatables').DataTable().ajax.reload(null, false);
                }).catch(error => {

                    // Servidor respondeu com um código de status que não está na faixa 2xx
                    if (error.response) {

                        // Array de erros (pode ser status ou mensagens)
                        const arrStatusErros = [400, 403, 404, 500, 400, 'erro_de_conexao'];

                        // trata todos erros de response.status
                        if (arrStatusErros.includes(error.response.status)) {
                            $('#msgOperacao').text('[' + error.response.status + '] - Ocorreu um erro ao processar a requisição: ' + error.response.data.message).show();
                            return;
                        }

                        // vamos exibir todas as mensagens de erro específicas de validação abaixo de cada campo correspondente
                        $.each( error.response.data.errors, function( key, value ) {
                            $("#error-" + key ).text(value).show(); 
                        });
                        $('#msgOperacao').text(error.response.data.message).show();                 // mensagem geral
                        
                    } else if (error.request) {
                        // A requisição foi feita, mas não houve resposta
                        $('#msgOperacao').text('error.request: o Servidor não respondeu à requisição. Verifique sua rede"').show(); 
                    } else {
                        // Algo aconteceu ao configurar a requisição
                        $('#msgOperacao').text('error.desconhecido: ' +error.message).show();       // mensagem geral
                    }                    
                });  


                // $.ajax({
                //     type: "POST",
                //     // url: "organizacao/store",
                //     // data: formDados,
                //     url: "https://apieventos.voluntary.com.br/api/organizacao/",   // consumindo API externa via GET
                //     headers: {
                //         'contentType': 'application/json',                          // Tipo de conteúdo de envio (JSON)
                //         'Access-Control-Allow-Origin': '',                        
                //         'Authorization': 'Bearer 19|HUm7ao29lyhtDjo9BGKwL8DbbbNl22i79cIvURn07c5bfa08',
                //     },     
                //     dataType: 'json',               
                //     data: formDados,  
                //     // data: JSON.stringify(formDados),  

                //     cache: false,
                //     contentType: false,
                //     processData: false,
                //     success: function (data) {
                //         $("#alert .alert-content").text('Salvou registro ID ' + data.id + ' com sucesso.');
                //         $('#alert').removeClass().addClass('alert alert-success').show().delay(5000).fadeOut(1000);
                //         $('#editarModal').modal('hide');
                //         $('#datatables').DataTable().ajax.reload(null, false);
                //     },
                //     error: function (error) {
                //         if (error.responseJSON === 401 || error.responseJSON.message && error.statusText === 'Unauthenticated') {
                //             window.location.href = "{{ url('/') }}";
                //         }
                //         // validator: vamos exibir todas as mensagens de erro do validador
                //         // como o dataType não é JSON, precisa do responseJSON
                //         $.each( error.responseJSON.errors, function( key, value ) {
                //             $("#error-" + key ).text(value).show(); //show all error messages
                //         });
                //         $('#msgOperacao').text(error.responseJSON.message).show();
                //     }
                // });                
            });

            // põe o foco no primeiro campo do modal
            $('body').on('shown.bs.modal', '#editarModal', function () {
                $('#nome').focus();
            })

            /*
            * Atualiza a tabela de dados de registros
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


