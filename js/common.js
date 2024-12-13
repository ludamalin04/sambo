const burger = document.querySelector('.header-burger');
const list = document.querySelector('.header-list');
const action = document.querySelector('.header-action');

burger.addEventListener('click', () => {
    burger.classList.toggle('active');
    list.classList.toggle('visible');
    action.classList.toggle('visible');
})

// const newsText = document.querySelector('.news-text');
// const newsItem = document.querySelector('.news-item');
// const picture = document.querySelector('.news-img img');
// newsItem.forEach((item) => {
//     item.addEventListener('mouseover', () => {
//         item.prepend(newsText);
//         newsText.style.visibility = 'visible';
//         newsText.style.width = '100%';
//     })
//     item.addEventListener('mouseout', () => {
//         newsText.style.visibility = 'hidden';
//     })
// })
//
// picture.forEach((item) => {
//     item.addEventListener('mouseover', () => {
//         item.style.opacity = '0';
//     })
//     item.addEventListener('mouseout', () => {
//         item.style.opacity = '1';
//         item.style.transitionDuration = '0.5s';
//     })
// })


