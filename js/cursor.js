document.addEventListener('DOMContentLoaded', function() {
  // Create cursor element
  const cursor = document.createElement('div');
  cursor.className = 'custom-cursor';
  cursor.innerHTML = '<div class="cursor-plane"></div>';
  document.body.appendChild(cursor);
  
  // Position cursor
  const moveCursor = (e) => {
    const mouseX = e.clientX;
    const mouseY = e.clientY;
    
    // Calculate angle based on mouse movement
    const angle = Math.atan2(
      e.movementY,
      e.movementX
    ) * 180 / Math.PI;
    
    // Apply position and rotation
    cursor.style.transform = `translate3d(${mouseX}px, ${mouseY}px, 0)`;
    cursor.querySelector('.cursor-plane').style.transform = `rotate(${angle}deg)`;
  };
  
  // Handle click effect
  const handleClick = () => {
    cursor.style.transform += ' scale(0.7)';
    setTimeout(() => {
      cursor.style.transform = cursor.style.transform.replace(' scale(0.7)', '');
    }, 100);
  };
  
  // Event listeners
  document.addEventListener('mousemove', moveCursor);
  document.addEventListener('mousedown', handleClick);
  
  // Hide cursor when mouse leaves window
  document.addEventListener('mouseleave', () => {
    cursor.style.opacity = '0';
  });
  
  document.addEventListener('mouseenter', () => {
    cursor.style.opacity = '1';
  });
});