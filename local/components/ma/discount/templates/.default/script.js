class Discount {
    constructor(settings) {
        this.settings = settings;
        this.$container = BX(settings.container);
        this.$getButton = this.$container.querySelector('button[name="getDiscount"]');
        this.$checkButton = this.$container.querySelector('button[name="checkDiscount"]');
        this.$checkField = this.$container.querySelector('input[name="checkField"]');
        this.$resultContainer = this.$container.querySelector('.discount__info');

        this.$getButton.addEventListener('click', BX.proxy(this.getDiscount, this));
        this.$checkButton.addEventListener('click', BX.proxy(this.checkDiscountCode, this));
    }
    getDiscount() {
        BX.ajax.runComponentAction(this.settings.componentName, 'getDiscount', {
            mode: 'class',
            data: {
                params: this.settings.params
            }
        })
        .then(response => {
            this.printResult(response);
        });
    }
    checkDiscountCode() {
        if (!this.$checkField.value) {
            return false;
        }
        BX.ajax.runComponentAction(this.settings.componentName, 'checkDiscountCode', {
            mode: 'class',
            data: {
                code: this.$checkField.value,
                params: this.settings.params
            }
        })
        .then(response => {
            this.printResult(response);
        });
    }
    printResult(response) {
        const data = response.data;
        if (data.error && data.error.length > 0) {
            this.$resultContainer.innerHTML = data.error.join('<br>');
        } else {
            this.$resultContainer.innerHTML = `<div class="discount__percent">${data.percent}%</div>
                <div>${data.code}</div>
            `;
        }
    }
}
