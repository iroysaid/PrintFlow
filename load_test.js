import http from 'k6/http';
import { check, sleep } from 'k6';

export let options = {
    stages: [
        { duration: '30s', target: 20 }, // Simulate ramp-up to 20 users
        { duration: '1m', target: 20 },  // Stay at 20 users
        { duration: '30s', target: 0 },  // Ramp-down to 0 users
    ],
};

export default function () {
    let res = http.get('http://localhost:8085'); // Adjust URL/port to your app
    check(res, { 'status was 200': (r) => r.status == 200 });
    sleep(1);
}
