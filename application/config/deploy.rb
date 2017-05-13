set :application, 'shopping_admin'
set :repo_url, 'git@github.com:sleepinglion/shopping_codeigniter.git'
set :branch, 'master'
# Default branch is :master
# ask :branch, `git rev-parse --abbrev-ref HEAD`.chomp

# Default deploy_to directory is /var/www/my_app_name
set :deploy_to, '/BiO/Serve/Httpd/Gene/shopping'

# Default value for :format is :airbrussh.
# set :format, :airbrussh

# You can configure the Airbrussh format using :format_options.
# These are the defaults.
set :format_options, command_output: true, log_file: 'application/logs/capistrano.log', color: :auto, truncate: :auto

# Default value for :pty is false
set :pty, true

# Default value for :linked_files is []
append :linked_files, 'application/config/config.php', 'application/config/database.php', 'public/.htaccess'

# Default value for linked_dirs is []
append :linked_dirs, 'public/uploads', 'application/logs'


namespace :deploy do
  desc 'Make Cache Directory'
  task :make_cache_directory do
    on roles(:app), in: :sequence, wait: 1 do
      within release_path do
          execute "chmod -R 777 #{release_path}/application/cache"
      end
    end
  end

  after :finishing, 'deploy:make_cache_directory'
end

# Default value for default_env is {}
# set :default_env, { path: "/opt/ruby/bin:$PATH" }

# Default value for keep_releases is 5
# set :keep_releases, 5
