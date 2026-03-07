import './bootstrap';
import { Livewire, Alpine } from '../../vendor/livewire/livewire/dist/livewire.esm';
import Swal from 'sweetalert2';

window.Swal = Swal;

Alpine.store('sidebar', {
    isOpen: false,
    toggle() {
        this.isOpen = !this.isOpen;
    },
});

Livewire.start();