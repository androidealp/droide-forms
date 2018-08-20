# droideforms New Version 1.2.4

### Intro
You have imagined in a form system for joomla facing developers backend and frontend?

A system that is easy to declare and reset php and javascript functions, customize and create the layout of the form using visuals of your theme?

Now this is possible with the Droid-forms, designed and developed for programmers who want to make the most of Joomla resources and stylize the forms with the resources of the theme in use.

### How it works
droid-forms has been designed as a module to be easily manipulated, it you can:

* Add preset or custom validations,
* Configure e-mail sending (the module uses the configuration.php data)
* Create sending layout
* Create answer layout
* Create select or create form layout

droid-forms is designed to be extended, using triggers joomla you can handle the form submission process with the features:
* onDroideformsInit - before set load all parans
* onDroideformsBeforeLayout - before view layout form
* onDroideformsbeforeInitRemententeMensagem - before init recipient treatment
* onDroideformsAddvalidate - Add custom form validation  
* onDroideformsAddrules - add custom rules, to new fields not sets in admin.
* onDroideformsBeforePublisheLayout - Before send layout of the mensage
* onDroideformsPosSend - Control after send e-mail
* onDroideformsPosSendError - Control after error generate

** Important to uses the var $custom_vars in your custom plugins for specific html view. This var This variable was added in version 1.1 **

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


### layout send

Layout to send, check preview:

```html
<h3>Dados do Faturamento</h3>
  <p data-elemento="tp_pessoa">Tipo de pessoa: {tp_pessoa}</p>

  <div data-turnon="tp_pessoa==Pessoa Física">
    <p data-elemento="cpf">CPF: {cpf}</p>
  </div>

  <div data-turnon="tp_pessoa==Pessoa Jurídica">
        <p data-elemento="cnpj"><strong>CNPJ:</strong> {cnpj}</p>
        <p data-elemento="ie">Inscrição Estadual: {ie}</p>
        <p data-elemento="isento">Isento: {isento}</p>
  </div>

  <div data-boxforeach="boxclone"  style='background:#eee; padding:5px; margin-top:5px; margin:bottom:5px;'>
        <p data-foreach="phone">Area: {phone}</p>
        <p data-foreach="name">Material Proteger: {name}</p>
  </div>


```

### Plugins

| Name                    | Link |
|-------------------------|------------------------------------------------------------------|
|droide-formsrecaptcha    | [Download](https://github.com/androidealp/droide-formsrecaptcha) |
|droide-sendcart          | [Download](https://github.com/androidealp/droide-sendcart)       |
|droide-captcha           | [Download](https://github.com/androidealp/droide-captcha)        |
|droide-cart *(for ajax)* | [Download](https://github.com/androidealp/droide-cart)           |

### Versions

| Version | Link |
|---------|------|
| v 1.2.3   | [download - v 1.2.3 - j3.8.x](https://github.com/androidealp/droide-forms/archive/v1.2.3.zip) |
| v 1.2.2   | [download - v 1.2.2 - j3.5.x](https://github.com/androidealp/droide-forms/archive/v1.2.2.zip) |
| v 1.2.1   | [download - v 1.2.1 - j3.5.x](https://github.com/androidealp/droide-forms/archive/v1.2.1.zip) |
| v 1.1   | [download - v 1.1 - j3.5.x](https://github.com/androidealp/droide-forms/archive/v1.1.zip) |
| v 1.0   | [download - v 1.0 - j3.5.x](https://github.com/androidealp/droide-forms/archive/v1.0.zip) |


## issues solveds for 1.2.4
 * Add Trigger onDroideformsbeforeInitRemententeMensagem
 * Added feature for recipient to receive custom layout email
 * more fields in the module administrator

## issues solveds for 1.2.3
 * Added variable &$returnTrigger in method _sendEmail which walks between triggers onDroideformsBeforePublisheLayout and onDroideformsBeforeReturn
 * Attachment event error for multiple clicks on submit resolved
 * Added validator method for cpf, cnpj, data

### Documentation and wikis
* [Wiki for Droide forms 1.2.x releases *(portuguese)*](https://github.com/androidealp/droide-forms/wiki/DroideForms-1.2-Funcionalidades)
* [Plugin droide forms for capcha](https://github.com/androidealp/droide-forms/wiki/Plugin-droide-forms-for-capcha)
