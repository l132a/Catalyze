import styled from "styled-components";
import { Link } from "react-router-dom";
import FacebookIcon from "@material-ui/icons/Facebook";
import TwitterIcon from "@material-ui/icons/Twitter";
import SearchIcon from "@material-ui/icons/Search";
import YouTubeIcon from "@material-ui/icons/YouTube";
import { imgLogo } from "../../assets";

const Header = () => {
    return (
        <Container>
            <LogoContainer href="">
                <Logo src={imgLogo} />
            </LogoContainer>
            <NavContainer>
                <Ul>
                    <Li>
                        <Link to="/">Home</Link>
                    </Li>
                    <Li>
                        <Link to="/about">About</Link>
                    </Li>
                    <Li>
                        <Link to="/works">Works</Link>
                    </Li>
                    <Li>
                        <Link to="/services">Services</Link>
                    </Li>
                </Ul>
            </NavContainer>
            <SosmedContainer>
                <FacebookIcon style={{ margin: 10 }} />
                <TwitterIcon style={{ margin: 10 }} />
                <YouTubeIcon style={{ margin: 10 }} />
                <SearchIcon style={{ margin: 10 }} />
            </SosmedContainer>
        </Container>
    );
};

export default Header;

const Container = styled.nav`
    display: flex;
    justify-content: space-between;
    align-content: center;
    border-bottom: #c2c2c2 1px solid;
`;

const LogoContainer = styled.a`
    flex: 1;
    text-align: center;
    justify-content: center;
    align-items: center;
    display: flex;
    :hover {
        opacity: 0.8;
    }
`;

const Logo = styled.img`
    height: 40px;
    width: auto;
`;

const NavContainer = styled.div`
    align-items: center;
    flex: 1;
    justify-content: center;
    display: flex;
`;

const SosmedContainer = styled.div`
    justify-content: center;
    align-items: center;
    display: flex;
    text-align: center;
    flex: 1;
`;

const Ul = styled.ul`
    display: flex;
    flex-wrap: nowrap;
    overflow-x: auto;
    margin-top: 40px;
    margin-bottom: 37px;
    -webkit-overflow-scrolling: touch;
`;

const Li = styled.li`
    flex: 0 0 auto;
    -webkit-box-align: center;
    -webkit-box-pack: center;
    -webkit-tap-highlight-color: transparent;
    align-items: center;
    color: #636363;
    height: 100%;
    justify-content: center;
    text-decoration: none;
    -webkit-box-align: center;
    -webkit-box-pack: center;
    -webkit-tap-highlight-color: transparent;
    align-items: center;
    display: flex;
    font-size: 15px;
    line-height: 15px;
    margin: 0 20px;
    font-weight: bold;
    text-decoration: none;
    white-space: nowrap;
    font-family: "Montserrat";
`;
