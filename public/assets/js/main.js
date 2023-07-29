



function remove() {

    let id = document.getElementById('idItem').value;
    let url = document.getElementById('url').value;

    document.getElementById('deleteModal').innerHTML = `
    <form action="${url}/${id}" method="post">
        <input type="hidden" name="_method" value="DELETE">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <input name="token" type="text" id="token" placeholder="Token" class="form-control">
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-danger">Delete</button>
        </div>
    </form>
`
}

function show() {
    let idItemShow = document.getElementById('idItemShow').value;
    let urlShow = document.getElementById('urlShow').value;
    document.getElementById('deleteModal').innerHTML = `
    <form action="${urlShow}/${idItemShow}" method="post">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <input name="token" type="text" id="token" placeholder="Token" class="form-control">
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-success">Show</button>
        </div>
    </form>`

}


