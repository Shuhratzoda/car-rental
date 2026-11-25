const io=new IntersectionObserver(es=>es.forEach(x=>{if(x.isIntersecting){x.target.classList.add('in');io.unobserve(x.target)}}),{threshold:.1});
document.querySelectorAll('.reveal').forEach((el,i)=>{el.style.transitionDelay=(i%6)*70+'ms';io.observe(el)});
addEventListener('scroll',()=>document.getElementById('nav').classList.toggle('s',scrollY>20));
document.querySelectorAll('.tilt').forEach(c=>{c.addEventListener('mousemove',e=>{const r=c.getBoundingClientRect(),x=(e.clientX-r.left)/r.width-.5,y=(e.clientY-r.top)/r.height-.5;c.style.transform=`perspective(800px) rotateY(${x*8}deg) rotateX(${-y*8}deg) translateY(-4px)`});c.addEventListener('mouseleave',()=>c.style.transform='')});
document.querySelectorAll('[data-n]').forEach(el=>{const t=+el.dataset.n;let n=0;const s=Math.max(1,t/40);const id=setInterval(()=>{n=Math.min(t,n+s);el.textContent=Math.round(n);if(n>=t)clearInterval(id)},30)});
const S=document.getElementById('s'),E=document.getElementById('e');
if(S&&E){const u=()=>{const d=Math.max(1,Math.round((new Date(E.value)-new Date(S.value))/864e5)+1),p=+document.querySelector('.sum').dataset.price;document.getElementById('dd').textContent=d;document.getElementById('tt').textContent=(d*p).toFixed(2)+' $';E.min=S.value};S.onchange=E.onchange=u}
