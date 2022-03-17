# Get Action

Let's create a totally custom action. What if, when we are on the details page for a question, a new action called "view" takes us to the frontend for that question? Sounds good! Start in `QuestionCrudController.php`. We're adding a new action, so it will probably be inside of `configuredActions()`. And we already know how to add actions to different pages - with this `->add()` method. Let's try adding to `Crud::PAGE_DETAIL` a new action called `view`. There are a bunch of built-in action names, as we know, like `index` or `delete`, and we usually reference those with the `Action` constant. But in this case, we're making a new action, so let's just invent a string called `view` and see what happens. Refresh and... we are greeted with a nice error.

```
The "view" action is not a built-in action, so you can't
add or configure it via its name. Either refer to one of
the built-in actions or create a custom action called "view".
```

In the last chapters, we talked about how, behind the scenes, each action is actually an action object. We don't need to worry about that most of the time, but when we create a custom action, we'll actually *need* to create our own custom action object. We'll do this up above the `return`. Say `$viewAction = Action::new()`, and then we'll pass it the same name that I just invented: `view`. Then, down here, instead of the string, you can see that this argument takes an `actionNameOrObject` if it's a built-in action or object. So I'll pass in that new `$viewAction` variable. Refresh again to see... *another* error:

```
Actions must link to either a route, a CRUD action, or a URL.
```

And then it gives us three different methods we can use to get that set up. That's a pretty great error message. It sounds like `linkToRoute()` or `linkToUrl()` is what we need.

So, up here, let's modify our action, and we *could* use `->linkToRoute()`, but as we already know from other places in our system, this is going to generate a URL to the route with all the extra admin query parameters, and we don't really want that. Instead, let's use `->linkToUrl()`. The problem is we can't just say `$this->generateUrl()` right here, because we need to know which question we're generating the URL from, and we don't have that right here.

Fortunately, the argument takes a string or a callable. Let's try that. Pass a `function()` here, and then to see what we can pass to this function, I'll use a little trick. I'll `dd(func_get_args())`, then back in the browser... awesome! We are apparently passed *one* argument, which is the `Question` object. That's beautiful, because we can use that to generate a URL. Say `return &this->generateUrl()` and our frontend route name, which is `app_question_show`. Then, this needs a `slug` route wild card set to the question slug. To get that, let me add the `Question $question` argument to our method and say `$question->getSlug()`. Awesome!

Try it now. And... yes! We have a "View" button. If we click it... it works! Now, just like any other action, we can modify it. We can say `->addCssClass('btn btn-success')`, `->setIcon('fa fa-eye`), and `->setLabel('View on site')` - all things that we've done before on other actions. Refresh and... that looks great! If we want to include this action on other pages, we can. Right now, if you go to the index page, there's no "view on frontend" action, but we *can* add it because we created this nice `$viewAction` object. So, down here at the bottom, we can just reuse that: `->add(Crud::PAGE_INDEX)`. We'll also pass that same `$viewAction` object. Refresh and... beautiful! You can see the `btn` styling didn't really work well right here, so you might wanna remove that or clone the action and create two separate things. It's up to you.

Okay, so creating an action that links somewhere is cool. But what about a true custom action that connects to a custom controller with custom logic? Let's add a custom action that allows moderators to approve questions. That's next.
