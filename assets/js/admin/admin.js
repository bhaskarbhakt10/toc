/***
 * 
 * Tabs switcher
 * 
 */

const switchTabs = (tabs, Wrapper, Content) => {

    tabs.forEach(tab => {

        tab.addEventListener(`click`, (e) => {

            e.preventDefault();

            let target = e.target;

            let Li = e.target;

            if (target.nodeName === 'LI') {
                target = target.querySelector(`a`);
            }
            if (target.nodeName === 'A') {
                Li = target.closest(`li`);
            }

            const link = target.getAttribute(`href`);

            tabs.forEach(tab => {
                tab.classList.remove(`active`);
            })
            Content.forEach(content => {
                content.classList.remove(`active`);
            })




            Li.classList.add(`active`);

            Wrapper.querySelector(`${link}`).classList.add(`active`);



        });
    });

}

const InitTabsSwitcher = () => {

    const tabs = document.querySelectorAll(`.setting__tabItem`) || null;
    const Wrapper = document.querySelector(`.tabs__wrapper`) || null;
    const Content = document.querySelectorAll(`.settings__content `) || null;

    if (tabs === null || Wrapper === null || Content === null) {
        return;
    }

    switchTabs(tabs, Wrapper, Content);


}

InitTabsSwitcher();