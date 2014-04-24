function printContent(contentId) {
    var content = $('#'+contentId);
    if(content.length) {
        var printWindow = window.open('','PrintWindow','width=700,height=700,top=250,left=345,toolbar=no,menubar=no,resizable=yes,scrollbars=yes,status=no');

        if(printWindow) {
            printWindow.document.write(content.html());
            printWindow.document.close();
            printWindow.focus();

            setTimeout(function(){if(printWindow.print()) {
                printWindow.close();
            }},3000);
        }
    }
}