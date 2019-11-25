{% extends 'layout.volt' %}

{% block title %}Add New Idea{% endblock %}

{% block styles %}

{% endblock %}

{% block content %}
{{ flashSession.output() }}

{{ form('idea/add', 'method': 'POST')}}
    <div class='form-group'>
        <label for='title'>Title</label>
        {{ text_field('title', 'class': 'form-control', 'required': true, 'placeholder': 'e.g. Very Cool Idea') }}
    </div>
    <div class='form-group'>
        <label for='description'>Description</label>
        {{ text_field('description', 'class': 'form-control', 'required': true, 'placeholder': 'e.g. We will do fun stuff') }}
    </div>
    <div class='form-group'>
        <label for='author_name'>Your Name</label>
        {{ text_field('author_name', 'class': 'form-control', 'required': true, 'placeholder': 'e.g. John Doe') }}
    </div>
    <div class='form-group'>
        <label for='author_email'>Your E-mail</label>
        {{ text_field('author_email', 'class': 'form-control', 'required': true, 'placeholder': 'e.g. john.doe@mail.com') }}
    </div>
    {{ submit_button('Submit', 'type': 'button', 'class': 'btn btn-primary') }}
{{ end_form() }}
{% endblock %}

{% block scripts %}

{% endblock %}