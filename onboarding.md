# Onboarding Doc for Collaborators on the Siggraph Wordpress Plugin for Search and Filtering

Welcome to the project. 


# How this project is structured

The folder structure is that of a standard wordpress
plugin, adapted for clarity 

### Quickstart
1. Clone the Siggraph Archive Docker Repo

    ```bash
    git clone https://github.com/ACM-SIGGRAPH-History-Archive/siggraph-archive-website.git
    cd siggraph-archive-website
    ```

2. Clone this code repository to a local development directory inside the
   launch directory for the SIGGRAPH wordpress docker-compose setup.

   ```bash
   mkdir plugins && cd plugins
   git clone https://github.com/kenjstewart/search-filter-display.git && cd ..
   ```

3. Once cloned do NOT launch`docker-compose`yet as we need to edit it first.
   Edit the docker-compose.yml to add this directory to the volumes definition 
   for the "web" service (listed at the top of the file).

    ```yaml
    volumes:
        - ./plugins/search-filter-display:/var/www/html/wp-content/plugins/search-filter-display
        - ./debug.log:/var/www/html/wp-content/debug.log
    ```

4. This will make the directory read locally so you can modify it in realtime
   and see the result in the docker when it is running. Also creating an entry
   for the log file locally makes the process of tracing issues much simpler,
   instead of navigating the container every time.



