class TokenManager {
    static getToken() {
        return JSON.parse(localStorage.getItem('authToken'));
    }

    static setToken(tokenData) {
        localStorage.setItem('authToken', JSON.stringify(tokenData));
    }

    static removeToken() {
        localStorage.removeItem('authToken');
    }

    static isAuthenticated() {
        const token = this.getToken();
        return token && token.apiKey;
    }

    static getApiKey() {
        const token = this.getToken();
        return token ? token.apiKey : null;
    }
}


export  default TokenManager;
