# Install

Coming soon...

Hey friends. Well, now we are in for a treat with this tutorial. It's EasyAdmin. My
favorite admin generated for Symfony. It just gives you so much, many feature out of
the box and it looks great. It shouldn't really be a surprise because its creator is
the always impressive Javier Aguila. So let's have some fun and learn how to bend
easy admin to our will because getting a lot of features for free is great. As long
as we can and extend everything. When we need to, to get the most easy out of 
EasyAdmin, you should definitely code along with me. You probably know the drill,
download the course code from this page and unzip it to find a start directory with
the same code that you see here. Check out the `README.md` file for all these setup
goodies. I've already done all of these steps except for two, the first find your
terminal. The first is to compile the assets. So 

```terminal
yarn install
```

I actually did run
that already to save time <affirmative> and then I'll actually compile my assets by
running

```terminal
yarn watch
```

You can also run `yarn dev-server`, which is another cool way to do this. Perfect. Then
I'm gonna open up another tab and say, 

```terminal
symfony serve -d
```

and that's gonna start a
local web server using the Symfony binary and it starts at `127.0.0.1:8000`. I'm
gonna be lazy hold command and click that to say hello to caldron overflow. You've
been doing our Symfony five series. You're probably familiar with this project, but
this is not a Symfony 5 project. This is a Symfony 6 project. Ooh, but don't
worry if you're using Symfony 5, it's not gonna be, it won't be any different.

Now most of the code inside of this project, you don't really need to worry about.
The most important thing is probably our `src/Entity/` directory. Our doctrine
entities, our site has questions. Each question has an answer, has a number of
answers. Each question belongs to a single topic and then we have a user entity. Our
goal in this tutorial is to create a rich admin section that allows our admin users
to manage these. So let's get EasyAdmin installed, head terminal and run 

```terminal
composer require admin
```

which is a flex alias, four `easycorp/easyadmin-bundle`. Notice that
this downloads the brand new version, 4 of EasyAdmin, which only works with
Symfony 6. So using Symfony 5 run `composer require admin:^3` to
get version 3 of EasyAdmin bundle right now, version 4 and version 3 are
identical. So you won't notice any differences, but going forward, new features will
only be added to version 4. Cool. So this is, is now installed now what? Well, we
need a dashboard, a starting page for our admin. Let's generate that next.

