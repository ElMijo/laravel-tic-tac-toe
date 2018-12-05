# Tic Tac Toe

[![Build Status](https://travis-ci.org/ElMijo/laravel-tic-tac-toe.svg?branch=master)](https://travis-ci.org/ElMijo/laravel-tic-tac-toe)

This is a simple application that shows an easy way to integrate Vue and Laravel.

## Install

```
git clone https://github.com/ElMijo/laravel-tic-tac-toe.git
cd laravel-tic-tac-toe
./setup
./up
```

## Create demo users

This command create two users **userone@game.com** and **usertwo@game.com** with password **secret**.

'''
docker run --rm --interactive --tty --volume $PWD:/app php artisan db:seed
'''
