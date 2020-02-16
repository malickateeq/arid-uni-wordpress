import $ from 'jquery';

class Like
{
    constructor()
    {
        this.events();
    }

    events()
    {
        $(".like-box").on('click', this.heartClickDispatcher.bind(this))
    }
    // methods
    heartClickDispatcher(e)
    {
        var $like_box = $(e.target).closest(".like-box"); // where ever clicked in box find its closest parent "like-box"

        if( $like_box.attr('data-exists') == 'yes' )
        {
            this.delete_like($like_box);
        }
        else
        {
            this.create_like($like_box);
        }
    }
    create_like($like_box)
    {
        $.ajax({
            beforeSend: (xhr)=>{
                xhr.setRequestHeader('X-WP-Nonce', uni_data.nonce);
            },
            url: uni_data.root_url+'/wp-json/uni/v1/manage_like',
            type: 'POST',
            data:{'professor_id': $like_box.data('professor')},
            success: (response)=>{
                $like_box.attr("data-exists", "yes");
                var $like_count = parseInt($like_box.find(".like-count").html(), 10);
                $like_count++;
                $like_box.find(".like-count").html($like_count);
                $like_box.attr("data-like", response);
                console.log(response);
            },
            error: (response)=>{
                console.log(response);
            },
        });
    }
    delete_like($like_box)
    {
        $.ajax({
            beforeSend: (xhr)=>{
                xhr.setRequestHeader('X-WP-Nonce', uni_data.nonce);
            },
            url: uni_data.root_url+'/wp-json/uni/v1/manage_like',
            type: 'DELETE',
            data: {'like': $like_box.attr('data-like')},
            success: (response)=>{
                $like_box.attr("data-exists", "no");
                var $like_count = parseInt($like_box.find(".like-count").html(), 10);
                $like_count--;
                $like_box.find(".like-count").html($like_count);
                $like_box.attr("data-like", '');
                console.log(response);
            },
            error: (response)=>{
                console.log(response);
            },
        });
    }
}
export default Like;