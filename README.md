#   1. Instalando o ambiente da Aplicação com Docker
    Requisito: Esteja com o Docker instalado no computador
    Nota: este Docker Container tem: Web Server Nginx 1.25, PHP 8.2, MySql última versão, PHPMyAdmin última versão e Redis
    1.1 Entrar na pasta de seus projetos
    1.2 Criar uma pasta para o projeto
    1.3 Clone o repositório do projeto: git clone git@github.com:emfeuzebio/containerPHP.git
    1.4 Acessar a pasta do projeto via terminal
    1.5 Abrir o VSCode via terminal: code .
    1.6 Ativar o Container execundo o docker-compose.yml com o comando: docker-compose up

#   2. Instalando o Laravel
    Requisito: Entrar na pasta do projeto, abrir o VSCode e um termninal dentro dele
    2.1 Executar: composer create-project --prefer-dist laravel/laravel application
    2.2 Entrar na pasta application: cd application
    2.3 Excutar: php artisan key:generate --ansi
    2.4 Abrir o .env e copiar o rash da chave APP_KEY=
    2.5 Colar o rash no arquivo docker-compose.yml no environment do app APP_KEY=
    2.6 Reiniciar o Docker Container

#   3. Configurando a conexão com Banco de Dados no Laravel
    3.1 Abrir o arquivo .env e editar as tags

    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=acl
    DB_USERNAME=root
    DB_PASSWORD=root

#   4. Aplicar a configurações no Container Docker
    4.1 Desativar o container: docker-compose down
    4.2 Ativar o container: docker-compose up

#   5. Acessar a Aplicação Laravel
    5.1 http://localhost:10000
        Bando de Dados: acl
        Usuário: root
        Senha: root

#   6. Acessar o PHPMyAdmin 
    5.1 http://localhost:10001

#   7. Criar o Banco de Dados e as tabelas usando Migrates
    Acompanhar: http://localhost:10001 
    7.1 cd application
    7.2 Criar o Banco de dados e as tabelas via Migrates: php artisan migrate
    7.3 Ver o status das migrates: php artisan migrate:status
    7.4 Se criar ou mudar alguma Migrate aplique com: php artisan migrate 
        (alterei a migrate, apliquei, mas deu: nothin to migrate, ver como funciona)

#   8. Popular as tabelas usando Seeder
    Acompanhar: http://localhost:10001 
    Nota 1: se precisar criar um seeder: php artisan make:seeder ContaSeeder
    Nota 2: para executar um único seeder: php artisan db:seed ContaSeeder
    8.1 cd application
    8.2 para executar todas as factories e seeders: php artisan db:seed
    PRONTO! Banco de Dados estará populado com os dados mínimos necessários

#   9. Instalar o Admin LTE versão 3 que usa o Bootstrap 4
    9.1 composer require jeroennoten/laravel-adminlte
    9.2 php artisan adminlte:install
    9.3 composer require laravel/ui                         # bootstrap
    9.4 php artisan ui bootstrap --auth                     # login authentication yes
    9.5 php artisan adminlte:install --only=auth_views      # yes
    9.5 php artisan adminlte:install --type=full --with=main_views
    9.6 npm install

        9.6.1 - Como atualizar as versões do npm e do node.js: https://www.youtube.com/watch?v=2M-BmwrKFRQ
        9.6.1 curl -o- https://raw.githubusercontent.com/nvm-sh/nvm/v0.39.7/install.sh | bash
            export NVM_DIR="$HOME/.nvm"
            [ -s "$NVM_DIR/nvm.sh" ] && \. "$NVM_DIR/nvm.sh"  # This loads nvm
            [ -s "$NVM_DIR/bash_completion" ] && \. "$NVM_DIR/bash_completion"  # This loads nvm bash_completion
        9.6.1 nvm -v
        9.6.2 Atualizar o Node.js para versão mais recente: nvm install 20

    9.7 npm run build
    9.8 Alterar a view Home para a cara do AdminLTE
        Abrir resources/views/home.blade.php e substituir todo o conteúdo pelo abaixo:

            @extends('adminlte::page')

            @section('title', 'Dashboard')

            @section('content_header')
                <h1>Dashboard</h1>
            @stop

            @section('content')
                <p>Welcome to this beautiful admin panel.</p>
            @stop

            @section('css')
                {{-- Add here extra stylesheets --}}
                {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
            @stop

            @section('js')
                <script> console.log("Hi, I'm using the Laravel-AdminLTE package!"); </script>
            @stop        

    PRONTO! Admin LTE instalado!

#   10. Personalizar a instalação do AdminLTE
    php artisan adminlte:plugins
    php artisan adminlte:plugins install                        # instala todos
    php artisan adminlte:plugins install  --plugin=datatables   # somente este


#   11. Yajara Data Tables
##  Exmplo prático
    https://www.youtube.com/watch?v=N69ZOg59exs&t=191s

##  Documentação
    https://yajrabox.com/docs/laravel-datatables/master/

##  Instalação
    composer require yajra/laravel-datatables:^10.0



##  Para estudo: usar dentro do PHP
{
    // Rollback em todas as migrações e migrar as tabelas novamente
    Artisan::call('migrate:refresh');

    // Alimentar as tabelas
    Artisan::call('db:seed', ['--class' => 'TabelaSeeder']);
}








    







    
#   10. Personalizar a instalação do AdminLTE
    php artisan adminlte:plugins
    php artisan adminlte:plugins install                        # instala todos
    php artisan adminlte:plugins install  --plugin=datatables   # somente este
    php artisan adminlte:plugins remove                         # remove todos
    php artisan adminlte:plugins remove  --plugin=datatables    # remove este











### Yajara Data Tables
# Exmplo prático
https://www.youtube.com/watch?v=N69ZOg59exs&t=191s

# Documentação
https://yajrabox.com/docs/laravel-datatables/master/

# instalação
composer require yajra/laravel-datatables:^10.0




















    

Aguardar montagem das imagens e o container subir


#   2. # Subindo o Docker Container




#   2. Configurando a Aplicação
##  2.1 Configuração do ENV
### 2.1.1 Banco de Dados
    2.1.1.1 MySQL        
    2.1.1.1 MySQL            

#   3. Configurando a Aplicação
##  3.1 Configuração do ENV
### 3.1.1 Banco de Dados
    3.1.1.1 MySQL        
    3.1.1.1 MySQL            



# Subindo o Docker Container



### Instalando o Laravel mais atual

## Preparação
# remova a pasta public da pasta application
# entre na pasta application

# inslale a versão mais atual do Laravel com o comando abaixo
composer create-project --prefer-dist laravel/laravel:^10.0 application

## Configuração
# copiar a chave que foi gerada ao final da instalação continda em APP_KEY do .env
# e colar no arquivo docker-compose.yml em app após volumes: e antes de depends_on:

    environment:
      - COMPOSER_HOME=/composer
      - COMPOSER_ALLOW_SUPERUSER=1
      - APP_ENV=local
      - APP_KEY=base64:xH4BmKDZPZ0pbhpsC+gmmyNor8rf8PzYVkkm1tY6L1w=

# Atualizar a conexão do banco de dados no .env

    DB_CONNECTION=mysql
    DB_HOST=db
    DB_PORT=3306
    DB_DATABASE=gpmil
    DB_USERNAME=root
    DB_PASSWORD=root

# caso o não seja gerado o APP_KEY no .env tente instalar o php-curl
sudo apt-get install php-curl

# desmontar o container
docker-compose down

# subir o container
docker-compose up

# acessar a aplicação Laravel
http://localhost:8000/

# acessar o MySQL via phpMyAdmin
http://localhost:8001

login: 
    servidor: db
    user: root
    pwd: root

# criar o banco de dados no MySQL
CREATE DATABASE IF NOT EXISTS gpmil;

# acessar o container [app]
docker compose exec app bash
ou
docker-compose exec app bash

# Pupular as tabelas essenciais do Laravel com php artisan migrate
cd application

php artisan migrate:install

php artisan migrate:status 

php artisan migrate

# falta carregar dados nas tabelas criadas com seeder


#### Instalando o AdminLTE no Laravel
https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Installation
https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Artisan-Console-Commands
no terminal do VsCode pasta application

# 1. Require the package
# On the root folder of your Laravel project, require the package using the composer tool:
composer require jeroennoten/laravel-adminlte

# 2. Install the package resources
# Install the required package resources using the next command:
php artisan adminlte:install

# 3. Install the legacy authentication scaffolding (optional)
composer require laravel/ui
php artisan ui bootstrap --auth
php artisan adminlte:install --only=auth_views
php artisan adminlte:install --only=config --only=main_views
php artisan adminlte:install --with=auth_views --with=basic_routes
php artisan adminlte:install --type=full --with=main_views

# 3.1 Verificar se todos os packages foram instalados
php artisan adminlte:status

# pronto: acesse home Laravel e terá no canto sup dir Log In e Register
apos se registrar, ao fazer login via dar um erro de vite

editar o arquivo
resources/views/layouts/app.blade.php
comentar a linha do Vite acima do </head>
<!-- @vite(['resources/sass/app.scss', 'resources/js/app.js']) -->

### por ver ainda uma forma de ao iniciar o container já fezer o migrate e o seeder dos dados iniciais

# Colocando tradução no AdminLTE
fonte: https://github.com/lucascudo/laravel-pt-BR-localization/tree/master?tab=readme-ov-file

Instalação
Em /application executar os seguinte comandos:

php artisan lang:publish
composer require lucascudo/laravel-pt-br-localization --dev
php artisan vendor:publish --tag=laravel-pt-br-localization

# Configure o Framework para utilizar 'pt-BR' como linguagem padrão
# Altere Linha 86 do arquivo config/app.php para:
'locale' => 'pt-br',
# Linha 99
'fallback_locale' => 'pt-BR',
# Linha 86 - mensagens de erro das validações de form
# Ajuste também o timezone
# Linha 73
'timezone' => 'America/Sao_Paulo'

# Ajuste conforme o nessessário para aquelas palavras que não foram traduzidas em:
/application/lang/vendor/pt-br


### Yajara Data Tables
# Exmplo prático
https://www.youtube.com/watch?v=N69ZOg59exs&t=191s

# Documentação
https://yajrabox.com/docs/laravel-datatables/master/

# instalação
composer require yajra/laravel-datatables:^10.0


### configurar o Menu do AdminLTE


### Configurar a master.blade do AdminLTE para carregar os css e js necessários ao DataTables
editar o template geral da aplicação em
resources/views/vendor/adminlte/master.blade.php

colocar os arquivos .css necessários 
colocar os arquivos .js necessários 




