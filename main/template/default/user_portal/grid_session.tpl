{% for row in session %}
<div id="session-{{ item.id }}" class="panel panel-default">
    <div class="panel-heading">
        <img id="session_img_{{ row.id }}" src="{{ "window_list.png"|icon(32) }}" width="32" height="32" alt="{{ row.title }}" title="{{ row.title }}" /> {{ row.title }}
        {% if row.edit_actions != '' %}
        <div class="pull-right">
            <a class="btn btn-default btn-sm" href="{{ row.edit_actions }}">
                <i class="fa fa-pencil" aria-hidden="true"></i>
            </a>
        </div>
        {% endif %}
    </div>
    <div class="panel-body">
        <div class="grid-courses">
            <div class="row">
            {% for item in row.courses %}
            <div class="col-md-4 col-sm-4 col-xs-6">
                <div class="items">
                    <div class="image">
                        <img src="{{ item.icon }}" class="img-responsive">
                        {% if item.category != '' %}
                        <span class="category">{{ item.category }}</span>
                        <div class="cribbon"></div>
                        {% endif %}
                        <div class="black-shadow">
                            <div class="author-card">  
                            {% for teacher in item.teachers %}
                                {% set counter = counter + 1 %}
                                {% if counter <= 3 %}
                                <a href="{{ teacher.url }}" class="ajax" data-title="{{ teacher.firstname }} {{ teacher.lastname }}">
                                    <img src="{{ teacher.avatar }}"/>
                                </a>
                                <div class="teachers-details">
                                     <h5>
                                        <a href="{{ teacher.url }}" class="ajax" data-title="{{ teacher.firstname }} {{ teacher.lastname }}">
                                            {{ teacher.firstname }} {{ teacher.lastname }}
                                        </a>
                                     </h5>
                                </div>       
                                {% endif %}
                            {% endfor %}
                            </div>
                        </div>
                    </div>
                    <div class="description">
                        <h4 class="title">
                            {% if item.visibility == constant('COURSE_VISIBILITY_CLOSED') %}
                                {{ item.title }} {{ item.code_course }}
                            {% else %}
                                <a href="{{ item.link }}">{{ item.title }} {{ item.code_course }}</a>
                            {% endif %}
                        </h4>
                        <div class="notifications">{{ item.notifications }}</div>

                    </div>
                </div>
            </div>
        {% endfor %}
        </div>
        </div>
    </div>
</div>
{% endfor %}
