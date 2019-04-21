# MarkenHund CMS

## Project setup
Create hosts entry:

    127.0.0.1 mahucms.loc

Start the project using docker:

    docker-compose up -d --build
    
Open URL in browser:

    Frontend:           mahucms.loc
    Administration:     mahucms.loc/wp-admin
    
Credentials Administration:

| User | Password |
|----|-----|
| admin | wordpress |

## Project deployment

The following requirements exist:

    git
    npm
    node
    rsync
    
On a linux distribution system, they can be installed with the following commands:

    sudo apt-get install git npm node rsync 
    
Running deployment on specific environments:

    ./deploy.sh -e dev