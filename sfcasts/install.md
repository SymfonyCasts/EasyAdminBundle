# Install

Hey friends! We are in for a treat with this tutorial! It's EasyAdmin, my
favorite admin generated for Symfony. It just gives you so many features out of the box and it looks great. This shouldn't really be a surprise because its creator is the always-impressive Javier Aguila. So let's have some fun and learn how to bend EasyAdmin to our will, because getting a lot of features for free is great, as long as we can extend everything when we need to. To get the most "easy" out of EasyAdmin, you should definitely code along with me. 

You probably know the drill: Download the course code from this page and unzip it to find a `start/` directory with the same code that you see here. Check out the `README.md` file for all these setup goodies. I've already done all of these steps except for two. 

Find your terminal and run:

```terminal
yarn install
```

I ran this already to save time, so I'll actually compile my assets by
running:

```terminal
yarn watch
```

You can also run `yarn dev-server`, which is another cool way to do this.

Perfect! Then I'm going to open up another tab and say: 

```terminal
symfony serve -d
```

That's going to fire up a local web server using the Symfony binary and it starts at `https://127.0.0.1:8000`. I'm gonna be lazy, hold "Cmd" and click that... to say "hello" to Cauldron Overflow! If you've been doing our Symfony 5 series, you're probably already familiar with this project. However, this is actually a Symfony 6 project, not Symfony 5. Don't worry if you're using Symfony 5, though. It won't be any different.

You don't really need to worry about most of the code inside this project. The most important thing is probably our `src/Entity/` directory. Our site has questions, and each `Question` has a number of answers. Each `Question` belongs to a single `Topic`... and then we have a `User` entity. Our goal in this tutorial is to create a rich admin section that allows our admin users
to manage these.

So let's get EasyAdmin installed! Find your terminal and run: 

```terminal
composer require admin
```

This is a flex alias for `easycorp/easyadmin-bundle`. Notice that this downloads the brand new version 4 of EasyAdmin, which only works with Symfony 6. So if you're using Symfony 5, run 

```terminal
composer require admin:^3
``` 

to get version 3 of the EasyAdmin bundle. Right now, version 4 and version 3 are identical, so you won't notice any differences, but going forward, new features will only be added to version 4.

Cool! So now that this is installed, what's next? Great question! For starters, we'll need a dashboard for our admin. Let's generate that next!
