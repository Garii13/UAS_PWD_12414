function hitungTotal() {
    var elPeriode = document.getElementById("periode");
    var display = document.getElementById("info_harga");

    var harga = 0;
    var namaPeriode = "";
    if (elPeriode.selectedIndex > 0) {
        harga = parseInt(elPeriode.options[elPeriode.selectedIndex].getAttribute("data-harga"));
        namaPeriode = elPeriode.options[elPeriode.selectedIndex].getAttribute("data-nama");
    }

    var total = harga; 
    display.innerHTML = "Estimasi: Rp " + formatRupiah(total) + " (" + namaPeriode + ")";
}

function formatRupiah(angka) {
    return angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}
