

const InitCollapsibleTOC = () => {

    const TocWrapper = document.querySelector(`.toc__element.toc__collapsible[data-element="toc"]`) || null;


    if (TocWrapper === null) {
        return;
    }

    console.log(TocWrapper);

    const TocHeading = TocWrapper.querySelector(`.toc__heading.toc__collapsibleHeading`);

    const collapsibleItems = TocWrapper.querySelector(`.toc__collapsibleWrapper`);

    
    TocCollapse(TocHeading, collapsibleItems);

}

const TocCollapse = (TocHeading, collapsibleItems) => {

    TocHeading.addEventListener('click', (e) => {
        e.preventDefault();
        e.target.classList.toggle('collapsed');
        collapsibleItems.classList.toggle('collapsed');

        if (!collapsibleItems.classList.contains('collapsed')) {

            const fullHeight = collapsibleItems.scrollHeight;
            collapsibleItems.style.height = fullHeight + "px";
        } else {

            collapsibleItems.style.height = collapsibleItems.scrollHeight + "px";
            requestAnimationFrame(() => {
                collapsibleItems.style.height = "0px";
            });
        }
    });

};


InitCollapsibleTOC();