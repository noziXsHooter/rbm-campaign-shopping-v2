
//LIDA COM OS HANDLERS DO SORTEIO

function sweepstake_handlers(handler=null)
{
    const baseUrl = window.location.href.split('?')[0]; // PEGA URL BASE
    const searchUrl = baseUrl + '?ct=sweepstake&mt=sweepstake_handlers&id='+ handler; // Combine the base URL and search parameter
    window.location.href = searchUrl; // REDIRECIONA PARA URL FINAL
}


function export_ofx_sweepstake(data=null)
{
    const baseUrl = window.location.href.split('?')[0]; // PEGA URL BASE
    const searchUrl = baseUrl + '?ct=sweepstake&mt=sweepstake_handlers&id='+ handler; // Combine the base URL and search parameter
    window.location.href = searchUrl; // REDIRECIONA PARA URL FINAL
}