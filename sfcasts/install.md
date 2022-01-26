# Installing EasyAdmin

Well hey friends! We are in for a *treat* with this tutorial! It's EasyAdmin: my
favorite admin generator for Symfony. It just... gives you so many features out of
the box. And it looks great! This shouldn't really be a surprise because its creator
is the always-impressive Javier Eguiluz.

So let's have some fun and learn how to bend EasyAdmin to our will. Because getting
a lot of features for free is great... as long as we can extend it to do crazy
things when we need to.

## Project Setup

To squeeze the most "easy" out of EasyAdmin, you should definitely code along with me.
You probably know the drill: download the course code from this page and unzip it to
find a `start/` directory with the same code that you see here. Check out the
`README.md` file for all the setup goodies. I've already done all of these steps
except for two.

For the first, find your terminal and run:

```terminal
yarn install
```

I ran this already to save time... so I'll skip to compiling my assets with:

```terminal
yarn watch
```

You can also run:

```terminal
yarn dev-server
```

Which can do cool things like update your CSS without refreshing.

Perfect! For the *second* thing, open up another tab and run:

```terminal
symfony serve -d
```

This fires up a local web server - using the Symfony binary - at
https://127.0.0.1:8000. I'll be lazy by holding `Cmd` and clicking the link to pop
open my browser. Say "hello" to... Cauldron Overflow! If you've been doing our
Symfony 5 series, you're definitely familiar with this project. But, this
is a *Symfony 6* project, not Symfony 5:

[[[ code('312def2054') ]]]

Oooo. If you *are* using Symfony 5, don't worry: very little will be different.

You don't need to worry too much about the majority of the code inside the project. The
most important thing is probably our `src/Entity/` directory. Our site has questions,
and each `Question` has a number of answers. Each `Question` belongs to a single
`Topic`... and then we have a `User` entity.

Our goal in *this* tutorial is to create a rich admin section that allows our admin
users to manage *all* of this data.

## Installing EasyAdmin

So let's get EasyAdmin installed! Find your terminal and run:

```terminal
composer require admin
```

This is a Flex alias for `easycorp/easyadmin-bundle`. Notice that it downloads the
shiny new version *4* of EasyAdmin, which only works with Symfony 6. So if you're using
Symfony 5, run:

```
composer require admin:^3
```

to get version 3. Right now, version 4 and version 3 are identical,
so you won't notice any differences. But going forward, new features will only be
added to version 4.

Cool! Now that this is installed, what's next? Ship it!? Well, before we start
deploying and celebrating our success... if we want to actually *see* something
on our site, we're going to need a dashboard. Let's generate that next!
