const title = document.getElementById('title').textContent;
const Excel = (type) => {
    var data = document.getElementById('transaction_table');
    var excelFile = XLSX.utils.table_to_book(data, {sheet: "sheet1"});
    XLSX.write(excelFile, { bookType: type, bookSST: true, type: 'base64' });
    XLSX.writeFile(excelFile, `${title}.${type}`);
}

const PDF = () => {
    html2canvas(document.getElementById('transaction_table'), {
        onrendered: function (canvas) {
            var data = canvas.toDataURL();
            var docDefinition = {
                content: [{
                    image: data,
                    width: 500
                }]
            };
            pdfMake.createPdf(docDefinition).download(`${title}.pdf`);
        }
    });
}
