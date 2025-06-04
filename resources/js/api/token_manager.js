class TokenManager {


    static getToken() {
        return JSON.parse(localStorage.getItem('user'));
    }

    static setToken(tokenData) {
        localStorage.setItem('user', JSON.stringify(tokenData));
    }

    static removeToken() {
        localStorage.removeItem('user');
    }

    static isAuthenticated() {
        const token = TokenManager.getToken();
        return token && token.apiKey;
    }

    static getApiKey() {
        const token = this.getToken();
        return token ? token.apiKey : null;
    }
}


export  default TokenManager;
