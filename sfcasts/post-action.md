# True Custom Action

The whole point of this "Pending Approval" section is to allow moderators to approve
or delete questions. We can *delete* questions... but there's no way to approve them.
Sure, we could add a little "Is Approved" checkbox to the form. But a *true*
"approve" action with a button on the detail or index pages would be *a lot* nicer.
It would also allow us to run custom code on approval if we need to. So let's
create another custom action.

## Adding the Action as a Button

Over in `QuestionCrudController`, say `$approveAction = Action::new()`... and
I'll make up the word `approve`. Down at the bottom, add that to the detail page:
`->add(Crud::PAGE_DETAIL, $approveAction)`.

[[[ code('a074145efa') ]]]

Before we try that, call `->addCssClass('btn btn-success')` and
`->setIcon('fa fa-check-circle')`. Also add `->displayAsButton()`.

[[[ code('774aee608e') ]]]

By default, an action renders as a *link*... where the URL is wherever you want it
to go. But in this case, we don't want approval to be done with a simple link
that makes a "GET" request. Approving something will modify data on the server...
and so it should really be a "POST" request. This will cause the action to render
as a *button* instead of a link. We'll see how that works in a minute.

## Linking to a CRUD Action

Ok, we *have* now created the action... but we need to link it to a URL or
to a CRUD action. In this case, we need a CRUD action where we can write the
approve logic. So say `linkToCrudAction()` passing the name of a method that
we're going to create later. Let's call it `approve`.

[[[ code('a7566eabaf') ]]]

Sweet! Refresh and... duh! The button won't be here... but if we go to the detail
page... got it! "Approve"!

## Overriding the Template to Add a Form

Inspect element and check out the source code. Yup! This literally rendered as
a button... and that's it. There's no form around this... and no JavaScript
magic to make it submit. We can click this all day long and absolutely *nothing*
happens. To make it work, we need to wrap it in a form so that, on click, it
submits a POST request to the new action.

How can we do that? By leveraging a custom template. We know that EasyAdmin has *lots*
of templates. Inside EasyAdmin... in its `Resources/views/crud/` directory, there's
an `action.html.twig` file. *This* is the template that's responsible for rendering
*every* action. You can see that it's either an `a` tag or a `button` based
on our config.

Copy the three lines on top that document the variables we have... and let's go create
our *own* custom template. Inside `templates/admin/`, add a new file called
`approve_action.html.twig`. Paste in the comments... and then... just to *further*
help us know what's going on, dump that `action` variable: `dump(action)`.

[[[ code('b6350b41ae') ]]]

To *use* this template, over in `QuestionCrudController`... right on the action,
add `->setTemplatePath('admin/approve_action.html.twig')`.

[[[ code('bb551c23ff') ]]]

Let's try it. Refresh and... cool! We see the dump and *all* the data on that
`ActionDto` object. The most important thing for *us* is `linkURL`. This contains
the URL we can use to execute the `approve()` action that we'll create in a minute.

And because this new template is *only* being used by our *one* action... we're free
to do whatever we want! All the other actions are still using the core
`action.html.twig` template. Add a form... with `action="{{ action.linkUrl }}"`...
and then `method="POST"`. Inside, we need the button. We *could* create it ourselves...
or we can be lazy and `{{ include('@EasyAdmin/crud/action.html.twig') }}`.

[[[ code('4824e1f1d9') ]]]

That's all we need! Reload the page... and inspect that element to see... exactly
what we want: a `form` with the correct action... and our button inside. Though,
we *do* need to fix the styling a little bit. Add `class="me-2"`.

[[[ code('7ee47cd5d3') ]]]

Refresh and... looks better!

Try clicking this. We get... a *giant error*! Progress!

```
The controller for URI "/admin" is not callable: Expected method "approve" on
[our class].
```

Let's add that custom controller method next, and learn how to generate URLs to
other EasyAdmin pages from inside PHP.
