document.addEventListener('DOMContentLoaded', () => {
    let main = document.querySelector('.main'),
        saveBtn = document.querySelector('.saveBtn'),
        form = document.querySelector('.recordForm'),
        inputs = form.querySelectorAll('input, select'),
        formButton = form.querySelector('button'),
        updateBtn = document.querySelector('.updateBtn'),
        closeBtn = document.querySelector('.recordFormCloseBtn'),
        editBtn = document.querySelector('.editBtn'),
        table = main.querySelector('table'),
        removeBtn = document.querySelector('.removeBtn');

    function addListeners() {

    // ####################################################
    // Открыть форму для добавления
    // ####################################################
    saveBtn.addEventListener('click', () => {
        inputs.forEach(input => input.value = '');
        showElement('.recordForm');
    })

    // ####################################################
    // Закрытие формы
    // ####################################################
    closeBtn.addEventListener('click', () => {
        form.classList.add('hidden');
    });



    // ####################################################
    // Отправить форму
    // ####################################################
    formButton.addEventListener('click', e => {
        e.preventDefault();
        let vals = {};

        for (let element of inputs) {
            if (element.required) {
                if (element.value == "") {
                    console.log(element);
                    alert ('Проверьте правильность формы');
                    return;
                }
            }
            vals[element.name] = element.value;
        };
        $.ajax({
            method: 'POST',
            contentType: 'application/json',
            data: JSON.stringify([vals]),
            dataType: 'json'
        }).then(response => {
            if (response.success) {
                showSuccessMessage();
                getRecords();
            } else showErrorMessage(response.message);
        });


    });
        
    // ####################################################
    // Открыть форму для редактирования
    // ####################################################
    editBtn.addEventListener('click', () => {
        let vals = getRowValues();
        if (vals === null) {
            alert('Не выбрана ни одна запись');
            return;
        }
        vals = vals[0];
        // выбрать запись и открыть форму
        showElement('.recordForm');

        inputs.forEach(input => {
            input.value = vals[input.name];
        });
    });

    // ####################################################
    // Удалить запись
    // ####################################################
    removeBtn.addEventListener('click', () => {
        let values = getRowValues();
        if (values === null) {
            alert('Не выбрана ни одна запись');
            return;
        }
        $.ajax({
            method: 'DELETE',
            url: '/index.php',
            contentType: 'application/json',
            data: JSON.stringify(values),
            dataType: 'json'
        }).then(response => {
            let errors = [];
            response.forEach(item => {
                if (!item.success) {
                    errors.push(item.recordId);
                }
            });
            if (errors.length > 0) {
                showErrorMessage(errors.join(', '));
            } else {
                showSuccessMessage();
                getRecords();
            }
        });
    });
    // ####################################################
    // Обновить список записей
    // ####################################################
    updateBtn.addEventListener('click', () => {
        getRecords();
    })

    // ####################################################
    // Заблокировать кнопки редактировать и добавить, если выбрано несколько записей
    // ####################################################
    table.addEventListener('click', () => {
        disableButtons();
    })

    }
    /**
    * Получить записи с сервера
    */
    function getRecords() {
        $.ajax({
            method: 'GET',
            url: '/index.php/records'
        }).then(response => {
            let main = document.querySelector('.main');
            main.innerHTML = response;
            //Чтобы не ломались кнопки при обновлении таблицы, вешаем обработчик еще раз 
            let table = document.querySelector('table');
            table.addEventListener('click', () => {
                disableButtons();
            })
        })
        }

        // Показать элемент
        function showElement(selector) {
        let form = document.querySelector(selector);
        if (form.classList.contains('hidden')) {
            form.classList.remove('hidden');
        } ;
        }

        function showSuccessMessage() {
        showElement('.success_message');
        }

        function showErrorMessage(message) {
        let em = document.querySelector('.error_message');
        em.innerHTML = message;
        showElement('.error_message');
    }

    // Создать объект с парами название поля : значение
    // из строки таблицы записей
    function getRowValues() {
        let selected = document.querySelectorAll('table .selected:checked'),
            inputValues = [];
        if (selected.length === 0) {
            return null;
        }
        selected.forEach((s) => {
            let tr = s.parentElement.parentElement
            tds = tr.querySelectorAll('td[class]'),
            vals = {};

            tds.forEach(td => {
                vals[td.className] = td.innerText;
            });
            inputValues.push(vals);
        });

        return inputValues;
    }

    function disableButtons() {
        selected = document.querySelectorAll('table .selected:checked');
        if (selected.length > 1) {
            editBtn.setAttribute('disabled', true);
            saveBtn.setAttribute('disabled', true);
        } else {
            editBtn.removeAttribute('disabled');
            saveBtn.removeAttribute('disabled');
        }
    }

    addListeners();
});
