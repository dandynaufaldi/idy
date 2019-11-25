{% extends 'layout.volt' %}

{% block title %}Rate Idea{% endblock %}

{% block styles %}

{% endblock %}

{% block content %}
{{ flashSession.output() }}

{{ form('idea/rate', 'method': 'POST') }}
    <div class='form-group'>
    <label for='name'>Name</label>
        {{ text_field('name', 'class': 'form-control', 'required': true, 'placeholder': 'e.g. John Doe') }}
    </div>
    <div class='form-group'>
        <label class="radio">{{ radio_field('id':'value1', 'name':'value', 'value', 'value': 1, 'required': true) }}1</label>
    </div>
    <div class='form-group'>
        <label class="radio">{{ radio_field('id':'value2', 'name':'value', 'value', 'value': 2) }}2</label>
    </div>
    <div class='form-group'>
        <label class="radio">{{ radio_field('id':'value3', 'name':'value', 'value', 'value': 3) }}3</label>
    </div>
    <div class='form-group'>
        <label class="radio">{{ radio_field('id':'value4', 'name':'value', 'value', 'value': 4) }}4</label>
    </div>
    <div class='form-group'>
        <label class="radio">{{ radio_field('id':'value5', 'name':'value', 'value', 'value': 5) }}5</label>
    </div>
    {{ hidden_field('id', 'value': idea_id ) }}
    {{ submit_button('Submit', 'type': 'button', 'class': 'btn btn-primary') }}

{{ end_form() }}
{% endblock %}

{% block scripts %}

{% endblock %}