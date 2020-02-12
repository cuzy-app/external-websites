#!/bin/sh
git pull; git add * 
git commit -m "update"
git push 
git show --stat HEAD 