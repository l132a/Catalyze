import React from "react";
import ReactDOM from "react-dom";
import { Footer, Header, SubHedaer } from "../components";
import HeadLine from "../components/headline";
import "../assets/styles/global.css";
import "@fontsource/montserrat";

function App() {
    return (
        <div className="App">
            <Header />
            <SubHedaer />
            <HeadLine />
            <Footer />
        </div>
    );
}

export default App;

if (document.getElementById("root")) {
    ReactDOM.render(<App />, document.getElementById("root"));
}
