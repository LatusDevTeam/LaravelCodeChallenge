FROM node:latest

WORKDIR /usr/src/app

COPY . ./

RUN if [ -d "node_modules" ]; then npm update; else npm install; fi

EXPOSE 5173