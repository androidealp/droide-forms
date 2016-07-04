# droide-forms [downlod 3.5.x - v 1.0](droide-forms.zip)

### Intro
You have imagined in a form system for joomla facing developers backend and frontend?

A system that is easy to declare and reset php and javascript functions, customize and create the layout of the form using visuals of your theme?

Now this is possible with the Droid-forms, designed and developed for programmers who want to make the most of Joomla resources and stylize the forms with the resources of the theme in use.

### How it works
droid-forms has been designed as a module to be easily manipulated, it you can:

* Add preset or custom validations,
* configure e-mail sending (the module uses the configuration.php data)
* Create sending layout
* Create answer layout
* Create select or create form layout

droid-forms is designed to be extended, using triggers joomla you can handle the form submission process with the features:
* onDroideformsInit - before set load all parans
* onDroideformsBeforeLayout - before view layout form
* onDroideformsAddvalidate - Add custom form validation  
* onDroideformsAddrules - add custom rules, to new fields not sets in admin.
* onDroideformsBeforePublisheLayout - Before send layout of the mensage
* onDroideformsPosSend - Control after send e-mail
* onDroideformsPosSendError - Control after error generate

The javascript was developed as modules using designer partner to be easily manipulated, and thus modify dynamic structures layout.

```javascript
var j = jQuery.noConflict();

j(document).ready(function(){
sendDroideForms.alert_class = 'uk-alert uk-alert-';
 sendDroideForms.divLoad = function(){
  return "<p class='uk-text-center'><i class='uk-icon-spinner uk-icon-spin'></i></p>";
 };
});
```

### Requirements

The system needs jquery, if your layout is using, do not need to activate. otherwise you need to turn on the module panel.


### Layouts

The system currently has two layouts. Because the system uses the CSS defined in the layout, it was only applied the html layout.

* Default containing html bootstrap
* YOOtheme containing html YOOtheme

### Documentation and wikis
* [Plugin droide forms for capcha](https://github.com/androidealp/droide-forms/wiki/Plugin-droide-forms-for-capcha)
 
