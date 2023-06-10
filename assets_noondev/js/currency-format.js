var currency = formatCurrency()

function formatCurrency() {
    return {
        rupiah: function (number) {
            return new Intl.NumberFormat(['ban', 'id'], {
                minimumFractionDigits: 0,
                maximumFractionDigits: 2,
            }).format(number);
        },
        removeRupiah:function(number){
            return number.toString().replaceAll(".","").replace(",",".");
        }
    };
}


const rupiahInput = document.querySelectorAll(".rupiah_currency");
rupiahInput.forEach((input)=>{
    input.addEventListener("click", function(){
        const v = this.value;
        this.value = currency.removeRupiah(v);
    });

    input.addEventListener("focusin", function(){
        const v = this.value;
        this.value = currency.removeRupiah(v);
    });

    input.addEventListener("focusout", function(){
        const v = this.value;
        this.value = currency.rupiah(v);
    });
})