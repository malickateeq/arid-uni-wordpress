import $ from 'jquery';
class MyNotes
{
    constructor()
    {
        // Initialize events
        this.events();
    }

    events()
    {
        $("#my-notes").on('click', '.delete-note', this.delete_note);
        $("#my-notes").on('click', '.edit-note',this.edit_note.bind(this));
        $("#my-notes").on('click', '.update-note', this.update_note.bind(this));
        $(".submit-note").on('click', this.create_note.bind(this));
    }

    // Methods
    edit_note(e)
    {
        var thisNote = $(e.target).parents("li");
        if(thisNote.data("state") == "editable")
        {
            this.make_note_readonly(thisNote);
        }
        else
        {
            this.make_note_editable(thisNote);
        }
    }
    make_note_editable(thisNote)
    {
        thisNote.find(".note-title-field, .note-body-field").removeAttr("readonly").addClass("note-active-field");
        thisNote.find(".update-note").addClass("update-note--visible");
        thisNote.find(".edit-note").html('<i class="fa fa-times" aria-hidden="true"></i>Cancel');
        thisNote.data("state", "editable");
    }
    make_note_readonly(thisNote)
    {
        thisNote.find(".note-title-field, .note-body-field").attr("readonly", "readonly").removeClass("note-active-field");
        thisNote.find(".update-note").removeClass("update-note--visible");
        thisNote.find(".edit-note").html('<i class="fa fa-pencil" aria-hidden="true"></i>Edit');
        thisNote.data("state", "cancel");
    }
    delete_note(e)
    {
        var thisNote = $(e.target).parents("li");

        // $.getJSON(''); // For GET request
        $.ajax({
            beforeSend: (xhr)=>{
                xhr.setRequestHeader('X-WP-Nonce', uni_data.nonce);
            },
            url: uni_data.root_url+'/wp-json/wp/v2/note/'+thisNote.data("id"),
            type: 'DELETE',
            success: (response)=>{
                thisNote.slideUp();
            },
            error: (response)=>{
            },
        });
    }
    update_note(e)
    {
        var thisNote = $(e.target).parents("li");

        var updatedPost = {
            'title': thisNote.find(".note-title-field").val(),
            'content': thisNote.find(".note-body-field").val(),
        }
        // $.getJSON(''); // For GET request
        $.ajax({
            beforeSend: (xhr)=>{
                xhr.setRequestHeader('X-WP-Nonce', uni_data.nonce);
            },
            url: uni_data.root_url+'/wp-json/wp/v2/note/'+thisNote.data("id"),
            type: 'POST',
            data: updatedPost,
            success: (response)=>{
                this.make_note_readonly(thisNote);
            },
            error: (response)=>{

            },
        });
    }
    create_note(e)
    {

        var newPost = {
            'title': $(".new-note-title").val(),
            'content': $(".new-note-body").val(),
            'status': 'publish',
        }
        // $.getJSON(''); // For GET request
        $.ajax({
            beforeSend: (xhr)=>{
                xhr.setRequestHeader('X-WP-Nonce', uni_data.nonce);
            },
            url: uni_data.root_url+'/wp-json/wp/v2/note/',
            type: 'POST',
            data: newPost,
            success: (response)=>{
                $(".new-note-title, .new-note-body").val('');
                $(`
                    <li data-id="${response.id}">
                        <input readonly type="text" class="note-title-field" value="${response.title.raw}">
                        <span class="edit-note"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</span>
                        <span class="delete-note"> <i class="fa fa-trash-o" aria-hidden="true"></i> Delete</span>
                        <textarea readonly class="note-body-field">${response.content.raw}</textarea>
                        <span class="update-note btn btn--blue btn--small"> <i class="fa fa-arrow-right" aria-hidden="true"></i> Save</span>
                    </li>
                `)
                .prependTo("#my-notes").hide().slideDown();
            },
            error: (response)=>{

            },
        });
    }

}
export default MyNotes;