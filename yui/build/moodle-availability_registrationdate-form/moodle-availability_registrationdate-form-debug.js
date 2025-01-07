YUI.add('moodle-availability_registrationdate-form', function (Y, NAME) {

	/**
 * JavaScript для редактирования условий registrationdate в форме.
 *
 * @module moodle-availability_registrationdate-form
 */
M.availability_registrationdate = M.availability_registrationdate || {};

/**
 * @class M.availability_registrationdate.form
 * @extends M.core_availability.plugin
 */
M.availability_registrationdate.form = Y.Object(M.core_availability.plugin);

// Поля времени, доступные для выбора.
M.availability_registrationdate.form.timeFields = null;

// Текст перед выпадающим списком
M.availability_registrationdate.form.description_before = null;

// Текст после выпадающего списка
M.availability_registrationdate.form.description_after = null;

// Раздел или модуль.
M.availability_registrationdate.form.isSection = null;


/**
 * Инициализирует этот плагин.
 *
 * @method initInner
 * @param {array} timeFields Набор временных полей
 * @param {string} description_before Строка перед списком
 * @param {string} description_after Строка после списка
 * @param {boolean} isSection Это раздел или нет
 */
M.availability_registrationdate.form.initInner = function(timeFields, description_before, description_after, isSection) {
    this.timeFields = timeFields;
	this.description_before = description_before;
    this.description_after = description_after;
    this.isSection = isSection;
};

M.availability_registrationdate.form.getNode = function(json) {
    var html = '<span class="availability_registrationdate">';

	html += '<span class="desc_before">' + this.description_before + '</span>';

    html += '<label><select name="regnumber">';
    for (var i = 1; i < 60; i++) {
        html += '<option value="' + i + '">' + i + '</option>';
    }

    html += '</select></label> ';
    html += '<label><select name="regdnw">';
    for (var i = 0; i < this.timeFields.length; i++) {
        html += '<option value="' + this.timeFields[i].field + '">' + this.timeFields[i].display + '</option>';
    }
    html += '</select></label> ';
    html += '<span class="desc_after">' + this.description_after + '</span>';
    var node = Y.Node.create('<span>' + html + '</span>');

    // Установим начальные значения, если они указаны.
	var value = 1;
    if (json.n !== undefined) {
        value = json.n;
    }
    node.one('select[name=regnumber]').set('value', value);

    value = 2;
    if (json.d !== undefined) {
        value = json.d;
    }
    node.one('select[name=regdnw]').set('value', value);

    // Добавьте обработчики событий (только в первый раз).
    if (!M.availability_registrationdate.form.addedEvents) {
        M.availability_registrationdate.form.addedEvents = true;
        var root = Y.one('.availability-field');
        root.delegate('change', function() {
            M.core_availability.form.update();
        }, '.availability_registrationdate select');
    }

    return node;
};

M.availability_registrationdate.form.fillValue = function(value, node) {
    value.n = Number(node.one('select[name=regnumber]').get('value'));
    value.d = Number(node.one('select[name=regdnw]').get('value'));
};

M.availability_registrationdate.form.fillErrors = function(errors, node) {
    this.fillValue({}, node);
};


}, '@VERSION@', {"requires": ["base", "node", "event", "moodle-core_availability-form"]});
