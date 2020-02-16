import $ from 'jquery';
class Search
{
    // 1. initiate
    constructor()
    {
        // add search HTML
        this.addSearchHTML();
        
        this.openButton = $(".js-search-trigger");
        this.closeButton = $(".search-overlay__close");
        this.searchOverlay = $(".search-overlay");
        this.resultDiv = $("#search-overlay__result");

        // properties
        this.isOverlayOpen = false;
        this.isSpinnerVisible = false;

        this.previousValue;

        this.searchField = $("#search-term");

        // activate event listeners
        this.events();

        this.typingTimer;
    }
    // 2. Events
    events()
    {
        this.openButton.on('click', this.openOverlay.bind(this));
        this.closeButton.on('click', this.closeOverlay.bind(this));

        // Shortcut keys events
        $(document).on("keydown", this.keyPressDispatcher.bind(this));

        this.searchField.on("keyup", this.typingLogic.bind(this));

    }
    // 3. Funcitons
    typingLogic()
    {
        if(this.searchField.val() != this.previousValue)
        {
            // Reset previous set timer
            clearTimeout(this.typingTimer);
            
            if(this.searchField.val())
            {
                if(!this.isSpinnerVisible)
                {
                    this.resultDiv.html('<div class="spinner-loader"></div>');
                    this.isSpinnerVisible =true;
                }
                this.typingTimer = setTimeout(this.getResults.bind(this), 750);
            }
            else
            {
                this.resultDiv.html("");
                this.isSpinnerVisible = false;
            }
        }
        this.previousValue = this.searchField.val();
    }
    getResults() {

        $.getJSON( uni_data.root_url + '/wp-json/uni/v1/search?term=' + this.searchField.val() , (results)=>
        {
            this.resultDiv.html(`

                <div class="row">

                    <div class="one-third">
                        <h2 class="search-overlay__section-title">General Informarion</h2>
                        ${results.generalInfo.length ? '<ul class="link-list min-list">' : '<p>No general information matches that search.</p>'}
                        ${results.generalInfo.map(post => `<li><a href="${post.permalink}">${post.title}</a> ${ post.post_type == 'post' ? `by ${post.author_name}` : ''} </li>`).join('')}
                        ${results.generalInfo.length ? '</ul>' : ''}
                    </div>

                    <div class="one-third">
                        <h2 class="search-overlay__section-title">Programs</h2>
                            ${results.programs.length ? '<ul class="link-list min-list">' : '<p>No programs matche that search.</p>'}
                            ${results.programs.map(post => `<li><a href="${post.permalink}">${post.title}</a></li>`).join('')}
                            ${results.programs.length ? '</ul>' : ''}

                        <h2 class="search-overlay__section-title">Professors</h2>
                            ${results.professors.length ? '<ul class="professor-cards">' : '<p>No professors match that search.</p>'}
                            ${results.professors.map(post => `
                                <li class="professor-card__list-item"> 
                                    <a class="professor-card" href="${post.permalink}">
                                        <img class="professor-card__image"  src="${post.image}" alt="">
                                        <span class="professor-card__name"> ${post.title} </span> 
                                    </a> 
                                </li>
                            `).join('')}
                            ${results.professors.length ? '</ul>' : ''}
                    </div>

                    <div class="one-third">
                        <h2 class="search-overlay__section-title">Campuses</h2>
                            ${results.campuses.length ? '<ul class="link-list min-list">' : '<p>No campuses match that search.</p>'}
                            ${results.campuses.map(post => `<li><a href="${post.permalink}">${post.title}</a></li>`).join('')}
                            ${results.campuses.length ? '</ul>' : ''}

                        <h2 class="search-overlay__section-title">Events</h2>
                            ${results.events.length ? '' : '<p>No event match that search.</p>'}
                            ${results.events.map(post => `
                                <div class="event-summary">
                                    <a class="event-summary__date t-center" href="${ post.permalink }">
                                        <span class="event-summary__month">
                                            ${post.month }
                                        </span>
                                        <span class="event-summary__day">${ post.day }</span>  
                                    </a>
                                    <div class="event-summary__content">
                                        <h5 class="event-summary__title headline headline--tiny"><a href="${ post.permalink }">${ post.title }</a></h5>
                                        <p>${post.description}<a href="${post.permalink}" class="nu gray">Learn more</a></p>
                                    </div>
                                </div>
                            `).join('')}
                    </div>

                </div>

            `);
            this.isSpinnerVisible = false;
        });

    }

    openOverlay()
    {
        this.searchOverlay.addClass('search-overlay--active');
        $("body").addClass("body-no-scroll");
        setTimeout( () => this.searchField.focus() , 301);
        this.isOverlayOpen = true;
        return false;
    }
    closeOverlay()
    {
        this.searchOverlay.removeClass('search-overlay--active');
        $("body").removeClass("body-no-scroll");
        this.isOverlayOpen = false;
        this.searchField.val('');
    }

    keyPressDispatcher(e)
    {
        if(e.keyCode == 83 && !this.isOverlayOpen && !$("input, textarea").is(":focus") )
        {
            this.openOverlay();
        }
        if(e.keyCode == 27 && this.isOverlayOpen)
        {
            this.closeOverlay();
        }
    }

    addSearchHTML()
    {
        $("body").append(`
            <div class="search-overlay">
                <div class="search-overlay__top">
                    <div class="container">
                        <i class="fa fa-search search-overlay__icon" aria-hidden="true"></i>
                        <input type="text" class="search-term" placeholder="What are you looking for?" id="search-term">
            
                        <i class="fa fa-window-close search-overlay__close" aria-hidden="true"></i>
                    </div>
                </div>
                <div class="container">
                    <div id="search-overlay__result"></div>
                </div>
            </div>
        `);
    }
}

export default Search;