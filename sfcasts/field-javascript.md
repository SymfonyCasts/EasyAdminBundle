# Custom Field JavaScript

Go to the Question edit page. Ok: the question itself is in a `textarea`, which
is nice. But it would be even *better* if we could have a *fancy* editor that
helps with our markup.

## Hello TextEditorField

Fortunately EasyAdmin has something *just* for this. In `QuestionCrudController`,
for the `question` field, instead of a textarea, change to `TextEditorField`.

Refresh the page and... we have a cute lil' editor for free! Nice!

If you look inside of `TextEditorField`... you can see a bit about how this
works. Most importantly, it calls `addCssFiles()` and `addJsFiles()`. Easy Admin
comes with extra JavaScript and CSS that adds this special editor functionality.
And by leveraging these two methods, that CSS and Javascript is *included* on the
page whenever this field is rendered.

## Adding JavaScript to our Admin Encore Entry

So this is nice... except that... our `question` field isn't meant to hold HTML.
It's meant to hold markdown... so this editor doesn't make a lot of sense.

Let's go back to using the `TextareaField`.

So we don't need a fancy field... but it *would* be really cool if, as we type
inside of here, a preview of the final HTML were rendered right below this.

Let's do that! For this to happen, we're going to write some custom JavaScript
that will render the markdown. We could also make an Ajax call to render the
markdown... it doesn't matter. Either way, we need to write custom JavaScript!

Open up the `webpack.config.js` file. We *do* have a custom `admin` CSS file. Now
we're *also* going to need a custom `admin.js` file. So up in the `assets/` directory,
right next to the main `app.js` that's included on the frontend, create a new
`admin.js` file.

Inside, we're going to import two things. First, import `./styles/admin.css` to
bring in our admin styles. And second, import `./bootstrap`.

This file is also imported by `app.js`. Its purpose is to start the Stimulus
application and load anything in our `controllers/` directory as a Stimulus
controller.

If you haven't used Stimulus before, it's not required to do custom JavaScript...
it's just the way that *I* like to write custom JavaScript... and I think it's
awesome. We have a big tutorial all about it if you want to jump in.

So the `admin.js` file imports the CSS file and it also initializes the Stimulus
controllers. Now over in `webpack.config.js`, we can change this to be a *normal*
entrypoint... and point it at `./assets/admin.js`.

The end result is that Encore will now output a built `admin.js` file and a built
`admin.css` file... since we're import CSS from our JavaScript.

And because we just made a change to the Webpack config file, find the terminal
tab that's running Encore, stop it with "control+C" and restart it:

```terminal-silent
yarn watch
```

Perfect! It says that the "admin" entrypoint is outputting an `admin.css` file
*and* an `admin.js` file. It also splits some of the code into a few other files
for performance.

Thanks to this change, if you go refresh any page... and view the page source, yup!
We still have a `link` tag for `admin.css` but now the admin *JavaScript* is also
being included, which is all of this stuff right here. We now have the ability
to add *custom* JavaScript.

## The Stimulus Controller

So here's the plan. We're going to install a JavaScript markdown parser called
snarkdown. Then, as we type into this box, in real time, we'll use it to render an
HTML preview below this. And to hook all of this up, we're going to write a Stimulus
controller.

Let's start by installing that library. Over in the main terminal tab, run:

```terminal
yarn add snarkdown --dev
```

Excellent! Next, up in `assets/controllers/`, create a new file called
`snarkdown_controller.js`. And because this tutorial is *not* a Stimulus tutorial,
I'll paste in some contents.

What's inside of here... isn't that important. But to get it to work, we're going
to need some custom attributes that will *attach* this controller to the form field.
Let's do that next *and* use a performance trick so that our new controller isn't
unnecessarily downloaded by frontend users.
