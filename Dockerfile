FROM ubuntu:latest
LABEL authors="noemie"

ENTRYPOINT ["top", "-b"]
